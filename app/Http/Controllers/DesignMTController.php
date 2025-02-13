<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DesignMTController extends Controller
{
    // 디자인 메인
    public function design(){
        $design = \App\Design::where('design_type', 'basic')->get();
        $design = $design->keyBy('design_code');

        return view('admin.admin_site_design', ['designs' => $design]);
    }

    // 기본정보입력
    public function basicinsert(Request $request){
        $company_name = $request->input('company_name');
        $ceo_name = $request->input('ceo_name');
        $zip_code = $request->input('zip_code');
        $address = $request->input('address');
        $address_detail = $request->input('address_detail');
        $academy_number = $request->input('academy_number');
        $academy_number_file = $request->file('academy_number_file');
        $academy_number_agency = $request->input('academy_number_agency');
        $business_number = $request->input('business_number');
        $sales_number = $request->input('sales_number');
        $sales_number_file = $request->file('sales_number_file');
        $sales_number_agency = $request->input('sales_number_agency');
        $additional_number = $request->input('additional_number');
        $hosting_service = $request->input('hosting_service');
        $representative_number = $request->input('representative_number');
        $representative_inquiry = $request->input('representative_inquiry');
        $alliance_inquiry = $request->input('alliance_inquiry');

        $this->saveDesignInfo('basic', 'company_name', $company_name, "");
        $this->saveDesignInfo('basic', 'ceo_name', $ceo_name, "");
        $this->saveDesignInfo('basic', 'zip_code', $zip_code, "");
        $this->saveDesignInfo('basic', 'address', $address, "");
        $this->saveDesignInfo('basic', 'address_detail', $address_detail, ""); 
        $this->saveDesignInfo('basic', 'academy_number', $academy_number, "");
        $this->saveDesignInfo('basic', 'academy_number_agency', $academy_number_agency, "");
        $this->saveDesignInfo('basic', 'business_number', $business_number, "");
        $this->saveDesignInfo('basic', 'sales_number', $sales_number, "");
        $this->saveDesignInfo('basic', 'sales_number_agency', $sales_number_agency, "");
        $this->saveDesignInfo('basic', 'additional_number', $additional_number, "");
        $this->saveDesignInfo('basic', 'hosting_service', $hosting_service, "");
        $this->saveDesignInfo('basic', 'representative_number', $representative_number, "");
        $this->saveDesignInfo('basic', 'representative_inquiry', $representative_inquiry, "");
        $this->saveDesignInfo('basic', 'alliance_inquiry', $alliance_inquiry, "");

        //파일이 있으면 저장.
        if($request->hasFile('academy_number_file')){
            $academy_number_file = $this->saveFile($academy_number_file, 'academy_number_file');
            $url = $academy_number_file[0];
            $file_name = $academy_number_file[1];
            $this->saveDesignInfo('basic', 'academy_number_file', $file_name, $url);
        }
        if($request->hasFile('sales_number_file')){
            $sales_number_file = $this->saveFile($sales_number_file, 'sales_number_file');
            $url = $sales_number_file[0];
            $file_name = $sales_number_file[1];
            $this->saveDesignInfo('basic', 'sales_number_file', $file_name, $url);
        }

        return response()->json(['resultCode' => 'success']);
    }

    // 디자인 정보 저장
    private function saveDesignInfo($type, $code, $value, $url = "") {
        //같은 코드가 있으면 업데이트, 없으면 저장
        $design = \App\Design::where('design_type', $type)->where('design_code', $code)->first();
        if ($design) {
            $design->design_value = $value;
            $design->design_url = $url;
            $design->save();
        }else{
            $design = new \App\Design;
            $design->design_type = $type;
            $design->design_code = $code;
            $design->design_value = $value;
            $design->design_url = $url;
            $design->save();
        }
        return;
    }

    // 파일 저장
    private function saveFile($file, $design_code){
        $originalName = $file->getClientOriginalName();
        $fileName = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        //time()이 중복되는 경우가 확인됨.
        // $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
        $fileNameToStore = $fileName . '_' . time() . '_' . uniqid() . '.' . $extension;
        $fileNameToStore = str_replace('.php', '', $fileNameToStore);
        $file->storeAs('public/uploads/design', $fileNameToStore);

        $bdupfile_path = "uploads/design/" . $fileNameToStore;

        // design_code로 찾아서 있으면 삭제진행
        $design = \App\Design::where('design_code', $design_code)->first();
        if ($design) {
            File::delete(storage_path('app/public/' . $design->design_url));
        }

        // $bdupfile_path, $originalName 두 변수를 배열로 만들어서 리턴
        return [$bdupfile_path, $originalName];
    }

    //파일 삭제
    public function filedelete(Request $request){
        $design_code = $request->input('design_code');
        $design = \App\Design::where('design_code', $design_code)->first();
        $path = "";
        if ($design) {
            File::delete(storage_path('app/public/' . $design->design_url));
            $path = storage_path('app/public/' . $design->design_url);
            $design->design_url = "";
            $design->design_value = "";
            $design->save();
        }
        return response()->json(['resultCode' => 'success', 'path' => $path]);
    }
}