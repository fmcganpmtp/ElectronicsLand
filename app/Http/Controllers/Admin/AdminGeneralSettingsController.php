<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\AdminGeneralSetting;
use Validator;

class AdminGeneralSettingsController extends Controller
{   //loading page
    function index()
    {
        return view("admin.general_settings.general_setting");
    }
    //list all setting items
    public function GeneralSettingslist()
    {
        $general_settings = AdminGeneralSetting::latest()->paginate(20);
        return view(
            "admin.general_settings.list-general-settings",
            compact("general_settings")
        );
    }
    //delete setting item
    public function deleteSettings($settingsId)
    {
        $general_settings = AdminGeneralSetting::find($settingsId);
        if (empty($general_settings)) {
            return redirect()
                ->route("general-setting.list")
                ->with("message", "No Settings Found");
        }
        $general_settings->delete();
        return redirect()
            ->route("general-setting.list")
            ->with("message", "Settings deleted successfully!");
    }
    //update  setting item
    public function updateSettings(Request $request)
    {
        //loop for update items
        for ($i = 0; $i < count($request->id); $i++) {
            DB::table("admin_general_settings")
                ->where("id", $request->id[$i])
                ->update([
                    "unique_key" => $request->unique_key[$i],
                    "display_name" => $request->display_name[$i],
                    "value" => $request->value[$i],
                ]);
        }
        return back()->with("message", "Record Successfully Updated!");
    }
    //insert general setting items
    public function insert(Request $request)
    {
        //validating modals
        if ($request->ajax()) {
            $rules = [
                "unique_key.*" => "required",
                "display_name.*" => "required",
                "value.*" => "required",
            ];
            $error = Validator::make($request->all(), $rules);
            if ($error->fails()) {
                return response()->json([
                    "error" => $error->errors()->all(),
                ]);
            }

            $unique_key = $request->unique_key;
            $display_name = $request->display_name;
            $value = $request->value;
            //lopp for inserting data
            for ($count = 0; $count < count($unique_key); $count++) {
                $data = [
                    "unique_key" => $unique_key[$count],
                    "display_name" => $display_name[$count],
                    "value" => $value[$count],
                ];
                $insert_data[] = $data;
            }

            AdminGeneralSetting::insert($insert_data);

            return response()->json([
                "success" => "Data Added successfully.",
            ]);
        }
    }
}
