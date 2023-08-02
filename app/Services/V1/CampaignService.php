<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\CampaignUploads;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\CampaignResource;
use App\Helpers\CommonHelper;
use App\Http\Resources\V1\CampaignDetailResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;

class CampaignService
{
    use CommonTrait;
    const module = 'Campaign';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $campaign = new Campaign();
        $campaignListData = $campaign->getCampaignsList($request);
        $getCampaignList =  CampaignResource::collection($campaignListData['data']);
        $responseArr['totalRecords'] = $campaign->getCampaignsListCount();
        $responseArr['filterResults'] = $campaignListData['count'];
        $responseArr['getCampaignList'] = $getCampaignList;
        return $this->successResponseArr($responseArr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $campaignCategoryId = $request->campaign_category_id;
        // save details
        $createCampaign = new Campaign();

        $createCampaign->campaign_category_id = $campaignCategoryId;
        $createCampaign->name = $request->name;
        $createCampaign->description = $request->description;
        $createCampaign->unique_code = $request->unique_code;
        $createCampaign->start_datetime = CommonHelper::getUTCDateTime($request->start_datetime);
        $createCampaign->end_datetime = CommonHelper::getUTCDateTime($request->end_datetime);
        $createCampaign->donation_target = $request->donation_target;
        $createCampaign->created_by = auth()->user()->id;
        $createCampaign->created_ip = CommonHelper::getUserIp();

        // upload file
        $uploadPath = Campaign::FOLDERNAME;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, $uploadPath, 1);
            if (!empty($data)) {
                $createCampaign->image = $data['filename'];
            }
        }

        // generate QR code 
        $qrFile = \QrCode::generate(url($request->unique_code));
        $qrFileName = 'qr_' . date('YmdHis') . '.svg';
        $qrFileNameWithPath = $uploadPath . $qrFileName;
        Storage::disk('public')->put($qrFileNameWithPath, $qrFile);
        $createCampaign->qr_image = $qrFileName;
        // generate QR code 
        $createCampaign->save();
        $lastId = $createCampaign->id;

        // save uploads & links 
        $campaignUploadsPath = CampaignUploads::FOLDERNAME;
        if ($request->has('upload_types')) {
            $uploadTypes = $request->upload_types;
            if (count($uploadTypes) > 0) {
                for ($i = 0; $i < count($uploadTypes); $i++) {
                    $saveUploadArr = new CampaignUploads();
                    $saveUploadArr->campaign_id = $lastId;
                    $saveUploadArr->upload_type = $request->upload_types[$i];
                    $saveUploadArr->title = $request->upload_title[$i];
                    $saveUploadArr->description = $request->upload_description[$i];
                    $saveUploadArr->created_by = auth()->user()->id;
                    $saveUploadArr->created_ip = CommonHelper::getUserIp();
                    if (isset($request->file('upload_file')[$i])) {
                        $uploadImage = $request->file('upload_file')[$i];
                        if (!empty($uploadImage)) {
                            $data = CommonHelper::uploadImages($uploadImage, $campaignUploadsPath, 1);
                            if (!empty($data)) {
                                $saveUploadArr->image = $data['filename'];
                            }
                        }
                    }
                    $saveUploadArr->save();
                }
            }
        }

        if ($request->has('link_type')) {
            $linkTypes = $request->link_type;
            if (count($linkTypes) > 0) {
                for ($i = 0; $i < count($linkTypes); $i++) {
                    $saveLinksArr = new CampaignUploads();
                    $saveLinksArr->campaign_id = $lastId;
                    $saveLinksArr->upload_type = $request->link_type[$i];
                    $saveLinksArr->title = $request->link_title[$i];
                    $saveLinksArr->description = $request->link_description[$i];
                    $saveLinksArr->link = $request->link[$i];
                    if (!empty($getAdminDetails)) {
                        $saveUploadArr->created_by = $getAdminDetails->id;
                        $saveUploadArr->created_ip = CommonHelper::getUserIp();
                    }
                    $saveLinksArr->save();
                }
            }
        }

        $getCampaignCategoryDetails = Campaign::where('id', $lastId)->first();
        $getCategoryDetails = new CampaignResource($getCampaignCategoryDetails);
        return $this->successResponseArr(self::module . __('messages.success.create'), $getCategoryDetails);
    }

    public function storeData(Request $request)
    {
        DB::beginTransaction();
        try {

            $inputs = $request->only(
                'name',
                'status',
                'description',
                'campaign_category_id',
                'start_datetime',
                'end_datetime',
                'donation_target',
                'unique_code',
                'image',
            );
            // prepare data

            $inputs['start_datetime'] = CommonHelper::getUTCDateTime($request->start_datetime);
            $inputs['end_datetime'] = CommonHelper::getUTCDateTime($request->end_datetime);
            $inputs['created_by'] = auth()->user()->id;
            $inputs['created_ip'] = CommonHelper::getUserIp();

            // upload file
            $uploadPath = Campaign::FOLDERNAME;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $data = CommonHelper::uploadImages($image, $uploadPath, 1);
                if (!empty($data)) {
                    $inputs['image'] =  $data['filename'];
                }
            }

            // generate QR code 
            $qrFile = \QrCode::generate(url($request->unique_code));
            $qrFileName = 'qr_' . date('YmdHis') . '.svg';
            $qrFileNameWithPath = $uploadPath . $qrFileName;
            Storage::disk('public')->put($qrFileNameWithPath, $qrFile);
            $inputs['qr_image'] =  $qrFileName;
            // generate QR code 

            //save data
            $campaign = Campaign::create($inputs);
            $campaignId = $campaign->id;

            // prepare uploads & links 
            $campaignUploadsPath = CampaignUploads::FOLDERNAME;
            $dateTime = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
            $filesInput = [];
            if ($request->has('files_uplaod')) {
                $files = $request->files_uplaod;
                foreach ($files as $file) {
                    $fileInput = [
                        'campaign_id' => $campaignId,
                        'upload_type' => 'Upload',
                        'title' => $file['title'],
                        'description' => $file['description'],
                        'created_by' => auth()->user()->id,
                        'created_ip' => CommonHelper::getUserIp(),
                        'updated_at' => $dateTime,
                        'created_at' => $dateTime,
                        'link' => null,
                    ];
                    $uploadImage = $file['image'] ?? null;
                    if (!empty($uploadImage)) {
                        $data = CommonHelper::uploadImages($uploadImage, $campaignUploadsPath, 1);
                        if (!empty($data)) {
                            $fileInput['image'] = $data['filename'];
                        }
                    }
                    $filesInput[] = $fileInput;
                }
            }

            if ($request->has('video')) {
                $links = $request->video;
                foreach ($links as $link) {
                    $linkInput = [
                        'campaign_id' => $campaignId,
                        'upload_type' => 'Links',
                        'title' => $link['title'],
                        'description' => $link['description'],
                        'created_by' => auth()->user()->id,
                        'created_ip' => CommonHelper::getUserIp(),
                        'link' => $link['link'],
                        'image' => null,
                        'updated_at' => $dateTime,
                        'created_at' => $dateTime,
                    ];
                    $filesInput[] = $linkInput;
                }
            }

            // insert uploads & links 
            if (!empty($filesInput)) {
                CampaignUploads::insert($filesInput);
            }

            $getCampaignCategoryDetails = Campaign::where('id', $campaignId)->first();
            $responseData = new CampaignResource($getCampaignCategoryDetails);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponseArr(__('messages.failed.general'));
        }
        return $this->successResponseArr(self::module . __('messages.success.create'), $responseData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateData(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $updateCampaign = Campaign::where('id', $id)->first();
            if (empty($updateCampaign)) {
                return $this->errorResponseArr('Campaign' . __('messages.validation.not_found'));
            }
            $updateCampaignAttachment = $updateCampaign->uploads;
            $updateCampaignAttachmentIds =  $updateCampaignAttachment->pluck('id')->toArray();
            $updateAttachment = [];
            // save details
            $campaignCategoryId = $request->campaign_category_id;
            $updateCampaign->campaign_category_id = $campaignCategoryId;
            $updateCampaign->name = $request->name;
            $updateCampaign->description = $request->description;
            $updateCampaign->start_datetime = CommonHelper::getUTCDateTime($request->start_datetime);
            $updateCampaign->end_datetime = CommonHelper::getUTCDateTime($request->end_datetime);
            $updateCampaign->donation_target = $request->donation_target;
            if (isset($request->status) && in_array($request->status, [0, 1])) {
                $updateCampaign->status = (int)$request->status;
            }


            // upload file
            $uploadPath = Campaign::FOLDERNAME;
            if ($request->hasFile('image')) {
                // Unlink old image from storage 
                $oldImage = $updateCampaign->getAttributes()['image'] ?? null;
                if ($oldImage != null) {
                    CommonHelper::removeUploadedImages($oldImage, $uploadPath);
                }
                // Unlink old image from storage 

                $image = $request->file('image');
                $data = CommonHelper::uploadImages($image, $uploadPath);
                if (!empty($data)) {
                    $updateCampaign->image = $data['filename'];
                }
            }
            // generate QR code 
            if ($updateCampaign->unique_code != $request->unique_code) {
                // Unlink qr from storage 
                $oldImage = $updateCampaign->qr_image;
                if (!empty($oldImage)) {
                    CommonHelper::removeUploadedImages($oldImage, Campaign::FOLDERNAME);
                }
                // Unlink qr from storage 

                $qrFile = \QrCode::generate(url($request->unique_code));
                $qrFileName = 'qr_' . date('YmdHis') . '.svg';
                $qrFileNameWithPath = $uploadPath . $qrFileName;
                Storage::disk('public')->put($qrFileNameWithPath, $qrFile);
                $updateCampaign->qr_image = $qrFileName;
                // $updateCampaign->qr_path = 'public/storage/' . $uploadPath;
            }
            $updateCampaign->unique_code = $request->unique_code;
            $updateCampaign->updated_by = auth()->user()->id;
            $updateCampaign->updated_ip = CommonHelper::getUserIp();
            $updateCampaign->update();

            // prepare uploads & links 
            $campaignId = $id;
            $campaignUploadsPath = CampaignUploads::FOLDERNAME;
            $dateTime = CommonHelper::getUTCDateTime(date('Y-m-d H:i:s'));
            // save uploads & links 
            if ($request->has('files_uplaod')) {
                $files = $request->files_uplaod;
                foreach ($files as $file) {
                    $updateAttachment[] = $file['id'];
                    //update data
                    if (isset($file['id']) && $file['id'] > 0) {
                        $uploadId = $file['id'];
                        $checkCampaignUploads = CampaignUploads::where('id', $uploadId)->first();
                        if (!empty($checkCampaignUploads)) {
                            $checkCampaignUploads->title = $file['title'];
                            $checkCampaignUploads->description = $file['description'];
                            if (isset($file['title']['images']) && is_file($file['title']['images'])) {
                                $uploadImage = $file['title']['images'];
                                if (!empty($uploadImage)) {
                                    // Unlink file from storage 
                                    $oldImage = $checkCampaignUploads->image;
                                    if (!empty($oldImage)) {
                                        CommonHelper::removeUploadedImages($oldImage, $campaignUploadsPath);
                                        CommonHelper::removeUploadedImages($oldImage, $campaignUploadsPath . 'thumb/');
                                    }
                                    // Unlink file from storage 
                                    $data = CommonHelper::uploadImages($uploadImage, $campaignUploadsPath, 1);
                                    if (!empty($data)) {
                                        $checkCampaignUploads->image = $data['filename'];
                                    }
                                }
                            }
                            $checkCampaignUploads->updated_by = auth()->user()->id;
                            $checkCampaignUploads->updated_ip = CommonHelper::getUserIp();
                            $checkCampaignUploads->update();
                        }
                    } else {
                        //indert data
                        $fileInput = [
                            'campaign_id' => $campaignId,
                            'upload_type' => 'Upload',
                            'title' => $file['title'],
                            'description' => $file['description'],
                            'created_by' => auth()->user()->id,
                            'created_ip' => CommonHelper::getUserIp(),
                            'updated_at' => $dateTime,
                            'created_at' => $dateTime,
                            'link' => null,
                        ];
                        $uploadImage = $file['image'] ?? null;
                        if (!empty($uploadImage)) {
                            $data = CommonHelper::uploadImages($uploadImage, $campaignUploadsPath, 1);
                            if (!empty($data)) {
                                $fileInput['image'] = $data['filename'];
                            }
                        }
                        $filesInput[] = $fileInput;
                    }
                }
            }

            if ($request->has('video')) {
                $links = $request->video;
                foreach ($links as $link) {
                    $updateAttachment[] = $link['id'];
                    //update data
                    if (isset($link['id']) && $link['id'] > 0) {
                        $uploadId = $link['id'];
                        $checkCampaignUploads = CampaignUploads::where('id', $uploadId)->first();
                        if (!empty($checkCampaignUploads)) {
                            $checkCampaignUploads->title = $link['title'];
                            $checkCampaignUploads->description = $link['description'];
                            $checkCampaignUploads->link = $link['link'];
                            $checkCampaignUploads->updated_by = auth()->user()->id;
                            $checkCampaignUploads->updated_ip = CommonHelper::getUserIp();
                            $checkCampaignUploads->update();
                        }
                    } else {
                        //indert data
                        $linkInput = [
                            'campaign_id' => $campaignId,
                            'upload_type' => 'Links',
                            'title' => $link['title'],
                            'description' => $link['description'],
                            'created_by' => auth()->user()->id,
                            'created_ip' => CommonHelper::getUserIp(),
                            'link' => $link['link'],
                            'image' => null,
                            'updated_at' => $dateTime,
                            'created_at' => $dateTime,
                        ];
                        $filesInput[] = $linkInput;
                    }
                }
            }

            //delete CampaignUploads that deleted on update
            $attachmentuploadPath = CampaignUploads::FOLDERNAME;
            $updateAttachment = array_filter($updateAttachment);
            $idsToDelete = array_diff($updateCampaignAttachmentIds, $updateAttachment);
            $toDeletes = CampaignUploads::whereIn('id', $idsToDelete)->get();
            foreach ($toDeletes as $toDelete) {
                if ($toDelete->upload_type == 'Upload') {
                    $oldImage = $toDelete->getAttributes()['image'] ?? null;
                    if ($oldImage != null) {
                        CommonHelper::removeUploadedImages($oldImage, $attachmentuploadPath);
                    }
                }
                $toDelete->delete();
            }
            // store data in files
            if (!empty($filesInput)) {
                CampaignUploads::insert($filesInput);
            }

            // $getCampaignCategoryDetails = Campaign::where('id', $id)->first();
            $getCategoryDetails = new CampaignResource($updateCampaign);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponseArr(__('messages.failed.general'));
        }
        return $this->successResponseArr(self::module . __('messages.success.update'), $getCategoryDetails);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getCampaignDetails = Campaign::withSum('donation', 'donation_amount')->where('id', $id)->orWhere('unique_code', $id)->first();
        if ($getCampaignDetails == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $getCampaignDetails->campaign_status_text = Campaign::CAMPAIGNSTATUSARR[$getCampaignDetails->campaign_status];
        $getCampaignDetails->status_text = $getCampaignDetails->status == 1 ? 'Active' : 'Deactive';
        $getCampaignDetails->donation_progress = empty($getCampaignDetails->donation_sum_donation_amount) ? 0 : number_format((($getCampaignDetails->donation_sum_donation_amount / $getCampaignDetails->donation_target) * 100), 2);
        $getCampaignDetails = CampaignDetailResource::make($getCampaignDetails);
        return $this->successResponseArr(self::module . __('messages.success.details'), $getCampaignDetails);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $updateCampaign = Campaign::where('id', $id)->first();
        if (empty($updateCampaign)) {
            return $this->errorResponseArr('Campaign' . __('messages.validation.not_found'));
        }
        // save details
        $campaignCategoryId = $request->campaign_category_id;
        $updateCampaign->campaign_category_id = $campaignCategoryId;
        $updateCampaign->name = $request->name;
        $updateCampaign->description = $request->description;
        $updateCampaign->unique_code = $request->unique_code;
        $updateCampaign->start_datetime = CommonHelper::getUTCDateTime($request->start_datetime);
        $updateCampaign->end_datetime = CommonHelper::getUTCDateTime($request->end_datetime);
        $updateCampaign->donation_target = $request->donation_target;

        // upload file
        $uploadPath = Campaign::FOLDERNAME;
        if ($request->hasFile('image')) {

            // Unlink old image from storage 
            $oldImage = $updateCampaign->getAttributes()['image'] ?? null;
            if ($oldImage != null) {
                CommonHelper::removeUploadedImages($oldImage, $uploadPath);
                CommonHelper::removeUploadedImages($oldImage, $uploadPath . 'thumb/');
            }
            // Unlink old image from storage 

            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, $uploadPath);
            if (!empty($data)) {
                $updateCampaign->cover_image = $data['filename'];
                $updateCampaign->cover_image_path = $data['path'];
            }
        }
        // generate QR code 
        if ($updateCampaign->unique_code != $request->unique_code) {
            // Unlink qr from storage 
            $oldImage = $updateCampaign->qr_image;
            if (!empty($oldImage)) {
                CommonHelper::removeUploadedImages($oldImage, Campaign::FOLDERNAME);
            }
            // Unlink qr from storage 

            $qrFile = \QrCode::generate(url($request->unique_code));
            $qrFileName = 'qr_' . date('YmdHis') . '.svg';
            $qrFileNameWithPath = $uploadPath . $qrFileName;
            Storage::disk('public')->put($qrFileNameWithPath, $qrFile);
            $updateCampaign->qr_image = $qrFileName;
            $updateCampaign->qr_path = 'public/storage/' . $uploadPath;
        }
        $updateCampaign->updated_by = auth()->user()->id;
        $updateCampaign->updated_ip = CommonHelper::getUserIp();
        $updateCampaign->update();

        $campaignUploadsPath = CampaignUploads::FOLDERNAME;
        // save uploads & links 
        if ($request->has('upload_types')) {
            $uploadTypes = $request->upload_types;
            if (count($uploadTypes) > 0) {
                for ($i = 0; $i < count($uploadTypes); $i++) {
                    if (isset($request->upload_id[$i])) {
                        $uploadId = $request->upload_id[$i];
                        $checkCampaignUploads = CampaignUploads::where('id', $uploadId)->first();
                        if (!empty($checkCampaignUploads)) {
                            $checkCampaignUploads->upload_type = $request->upload_types[$i];
                            $checkCampaignUploads->title = $request->upload_title[$i];
                            $checkCampaignUploads->description = $request->upload_description[$i];
                            if (isset($request->file('upload_file')[$i])) {
                                $uploadImage = $request->file('upload_file')[$i];
                                if (!empty($uploadImage)) {
                                    // Unlink file from storage 
                                    $oldImage = $checkCampaignUploads->image;
                                    if (!empty($oldImage)) {
                                        CommonHelper::removeUploadedImages($oldImage, $campaignUploadsPath);
                                        CommonHelper::removeUploadedImages($oldImage, $campaignUploadsPath . 'thumb/');
                                    }
                                    // Unlink file from storage 
                                    $data = CommonHelper::uploadImages($uploadImage, $campaignUploadsPath, 1);
                                    if (!empty($data)) {
                                        $checkCampaignUploads->image = $data['filename'];
                                    }
                                }
                            }
                            $checkCampaignUploads->updated_by = auth()->user()->id;
                            $checkCampaignUploads->updated_ip = CommonHelper::getUserIp();
                            $checkCampaignUploads->update();
                        }
                    } else {
                        $saveUploadArr = new CampaignUploads();
                        $saveUploadArr->campaign_id = $id;
                        $saveUploadArr->upload_type = $request->upload_types[$i];
                        $saveUploadArr->title = $request->upload_title[$i];
                        $saveUploadArr->description = $request->upload_description[$i];
                        if (isset($request->file('upload_file')[$i])) {
                            $uploadImage = $request->file('upload_file')[$i];
                            if (!empty($uploadImage)) {
                                $data = CommonHelper::uploadImages($uploadImage, $campaignUploadsPath, 1);
                                if (!empty($data)) {
                                    $saveUploadArr->image = $data['filename'];
                                }
                            }
                        }
                        $saveUploadArr->created_by = auth()->user()->id;
                        $saveUploadArr->created_ip = CommonHelper::getUserIp();
                        $saveUploadArr->save();
                    }
                }
            }
        }

        if ($request->has('link_type')) {
            $linkTypes = $request->link_type;
            if (count($linkTypes) > 0) {
                for ($i = 0; $i < count($linkTypes); $i++) {
                    if (isset($request->link_id[$i])) {
                        $linkId = $request->link_id[$i];
                        $checkCampaignLinks = CampaignUploads::where('id', $linkId)->first();
                        if (!empty($checkCampaignLinks)) {
                            $checkCampaignLinks->upload_type = $request->link_type[$i];
                            $checkCampaignLinks->title = $request->link_title[$i];
                            $checkCampaignLinks->description = $request->link_description[$i];
                            $checkCampaignLinks->link = $request->link[$i];
                            $checkCampaignLinks->updated_by = auth()->user()->id;
                            $checkCampaignLinks->updated_ip = CommonHelper::getUserIp();
                            $checkCampaignLinks->update();
                        }
                    } else {
                        $saveLinksArr = new CampaignUploads();
                        $saveLinksArr->campaign_id = $id;
                        $saveLinksArr->upload_type = $request->link_type[$i];
                        $saveLinksArr->title = $request->link_title[$i];
                        $saveLinksArr->description = $request->link_description[$i];
                        $saveLinksArr->link = $request->link[$i];
                        $saveLinksArr->created_by = auth()->user()->id;
                        $saveLinksArr->created_ip = CommonHelper::getUserIp();
                        $saveLinksArr->save();
                    }
                }
            }
        }

        $getCampaignCategoryDetails = Campaign::where('id', $id)->first();
        $getCategoryDetails = new CampaignResource($getCampaignCategoryDetails);
        return $this->successResponseArr(self::module . __('messages.success.update'), $getCategoryDetails);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campaign =  Campaign::where('id', $id)->first();
        if ($campaign == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        // Delete campaign category
        $campaign->deleted_by = auth()->user()->id;
        $campaign->deleted_ip = CommonHelper::getUserIp();
        $campaign->update();
        $deleteCampaign = $campaign->delete();
        // $updateCampaignUploads = CampaignUploads::where('campaign_id', $id)->update(['deleted_by' => auth()->user()->id, 'deleted_ip' => CommonHelper::getUserIp()]);
        $deleteCampaignUploads = CampaignUploads::where('campaign_id', $id)->delete();
        if ($deleteCampaign || $deleteCampaignUploads) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}
