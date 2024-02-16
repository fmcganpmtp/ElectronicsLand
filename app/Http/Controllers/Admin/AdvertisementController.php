<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use File;
use App\Models\Advertisement;
use App\Models\Contentpage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;
class AdvertisementController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:admin");
    }
    //loading page
    public function index()
    {
        return view("admin.advertisement.advertisement-list");
    }
    //loding add page
    public function create()
    {
        $page_list = Contentpage::get();
        return view("admin.advertisement.advertisement-create", [
            "Advertisement" => "",
            "page_list" => $page_list,
        ]);
    }
    //fetching ads positions available in page
    public function pagepositionavailable(Request $request)
    {
        $id = $request->get("id");
        $page_list = Contentpage::where("id", $id)
            ->pluck("page_position")
            ->first();
        echo json_encode($page_list);
    }
    //save advertisement in table
    public function store(Request $request)
    {
        $messages = ["media_file" => "Minimum Image Width :1000px"];
        //rules adding
        $request->validate([
            "page_id" => "required",
            "title" => "required",
            "media_type" => "required",
            "start_date" => "required|date",
            "end_date" => "required|date",
            // 'link_url'      => 'required',
            "media_file" => "required",
            "position" => "required",
        ]);

        $input = $request->all();
        $rx = '~
          ^(?:https?://)?                           # Optional protocol
           (?:www[.])?                              # Optional sub-domain
           (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
           ([^&]{11})                               # Video id of 11 characters as capture group 1
            ~x';
        //validation based on each advertisement type
        if (
            $input["media_type"] == "Image" ||
            $input["media_type"] == "YouTube"
        ) {
            if ($input["page_id"] == 11 && $input["position"] == "Middle") {
                $this->validate(
                    $request,
                    [
                        "media_file" =>
                            "required|image|mimes:jpeg,png,bmp,gif,svg|dimensions:min_width=1000",
                    ],
                    $messages
                );
            } else {
                $this->validate($request, [
                    "media_file" => "required|image|mimes:jpeg,png,bmp,gif,svg",
                ]);
            }
            if ($input["media_type"] == "YouTube") {
                $this->validate($request, ["link_url" => "regex:$rx"]);
                $input["link_url"] = $request->get("link_url");
            }
            if (request()->hasFile("media_file")) {
                $extension = request("media_file")->extension();
                $fileName = "advertise_pic" . time() . "." . $extension;
                $destinationPath = public_path() . "/uploads/advertiseImages";
                request("media_file")->move($destinationPath, $fileName);
                $input["media_file"] = $fileName;
            } else {
                $input["media_file"] = $Advertisement->media_file;
            }
        } elseif ($input["media_type"] == "Video") {
            $this->validate($request, [
                "media_file" => "required|file|mimetypes:video/mp4",
            ]);

            if ($request->hasFile("media_file")) {
                $extension = request("media_file")->extension();
                $destinationPath =
                    public_path() . "/uploads/advertisementVideo";
                $fileName = "advertise_video" . time() . "." . $extension;
                request("media_file")->move($destinationPath, $fileName);
                $input["media_file"] = $fileName;
            } else {
                $input["media_file"] = $Advertisement->media_file;
            }
        }

        Advertisement::create($input);
        return redirect()
            ->route("admin.advertisementlist")
            ->with("message", " Advertisement Added successfully!");
    }
    //fetching all advertisements
    public function getadvertisementlist(Request $request)
    {
        $columnIndex_arr = $request->get("order");
        $columnName_arr = $request->get("columns");
        $order_arr = $request->get("order");
        $search_arr = $request->get("search");

        $draw = $request->get("draw");
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page
        $columnIndex = $columnIndex_arr[0]["column"]; // Column index
        $columnName = $columnName_arr[$columnIndex]["data"]; // Column name
        $columnSortOrder = $order_arr[0]["dir"]; // asc or desc

        $searchValue = trim($request->get("search_key"));
        $position = $request->get("position");
        //total records
        $totalRecords = Advertisement::select("count(*) as allcount")->count();
        //total filtered count
        $totalRecordswithFilter = Advertisement::when(
            $searchValue != "",
            function ($query) use ($searchValue) {
                $query->where("title", "Like", "%" . $searchValue . "%");
            }
        )
            ->when($position != "", function ($query) use ($position) {
                $query->where("position", $position);
            })
            ->count();

        // Get records, also we have included search filter as well
        $records = Advertisement::leftJoin(
            "contentpages",
            "contentpages.id",
            "=",
            "advertisements.page_id"
        )
            ->orderBy($columnName, $columnSortOrder)
            ->when($searchValue != "", function ($query) use ($searchValue) {
                $query->where("title", "Like", "%" . $searchValue . "%");
            })
            ->when($position != "", function ($query) use ($position) {
                $query->where("position", $position);
            })
            ->select("advertisements.*", "contentpages.page")
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = [];

        foreach ($records as $record) {
            $data_arr[] = [
                "adv_id" => $record->adv_id,
                "title" => $record->title,
                "media_type" => $record->media_type,
                "position" => $record->position,
                "start_date" => date("d-m-Y", strtotime($record->start_date)),
                "end_date" => date("d-m-Y", strtotime($record->end_date)),
                "page" => $record->page,
            ];
        }
    //return data
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        ];
        echo json_encode($response);
    }
    //loading edit page
    public function edit($adv_id)
    {
        $Advertisement = Advertisement::where("adv_id", $adv_id)->first();
        $page_list = Contentpage::get();
        if (empty($Advertisement)) {
            return redirect()
                ->route("admin.advertisementlist")
                ->with("message", "No Advertisement found!");
        }
        return view(
            "admin.advertisement.advertisement-create",
            compact("Advertisement", "page_list")
        );
    }
    //update advertisement data
    public function update(Request $request)
    {
        $messages = ["media_file" => "Minimum Image Width :1000px"];
        //validation adding
        $input = $request->validate([
            "page_id" => "required",
            "title" => "required",
            "media_type" => "required",
            "start_date" => "required|date",
            "end_date" => "required|date",
            // 'link_url'      => 'required',
            "position" => "required",
        ]);

        $rx = '~
  ^(?:https?://)?                           # Optional protocol
   (?:www[.])?                              # Optional sub-domain
   (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
   ([^&]{11})                               # Video id of 11 characters as capture group 1
    ~x';

        $id = $request->get("adv_id");

        $Advertisement = Advertisement::where("adv_id", $id)->first();
        // rules adding on each advertisemet type
        if (
            $input["media_type"] == "Image" ||
            $input["media_type"] == "YouTube"
        ) {
            $input["link_url"] = $request->get("link_url");

            if ($input["media_type"] == "YouTube") {
                $this->validate($request, ["link_url" => "regex:$rx"]);
            }

            if (request()->hasFile("media_file")) {
                if ($input["media_type"] == "YouTube") {
                    if (
                        $input["page_id"] == 11 &&
                        $input["position"] == "Middle"
                    ) {
                        $this->validate(
                            $request,
                            [
                                "media_file" =>
                                    "required|image|mimes:jpeg,png,bmp,gif,svg|dimensions:min_width=1000",
                            ],
                            $messages
                        );
                    } else {
                        $this->validate($request, [
                            "media_file" =>
                                "required|image|mimes:jpeg,png,bmp,gif,svg",
                        ]);
                    }
                } else {
                    $input["link_url"] = "";

                    if (
                        $input["page_id"] == 11 &&
                        $input["position"] == "Middle"
                    ) {
                        $this->validate(
                            $request,
                            [
                                "media_file" =>
                                    "required|image|mimes:jpeg,png,bmp,gif,svg|dimensions:min_width=1000",
                            ],
                            $messages
                        );
                    } else {
                        $this->validate($request, [
                            "media_file" =>
                                "required|image|mimes:jpeg,png,bmp,gif,svg",
                        ]);
                    }
                }

                $extension = request("media_file")->extension();
                $fileName = "advertise_pic" . time() . "." . $extension;
                $destinationPath = public_path() . "/uploads/advertiseImages";
                request("media_file")->move($destinationPath, $fileName);
                $input["media_file"] = $fileName;
            } else {
                $input["media_file"] = $Advertisement->media_file;
            }
        } elseif ($input["media_type"] == "Video") {
            $input["link_url"] = "";
            if ($request->hasFile("media_file")) {
                $this->validate($request, [
                    "media_file" => "required|file|mimetypes:video/mp4",
                ]);

                $extension = request("media_file")->extension();
                $destinationPath =
                    public_path() . "/uploads/advertisementVideo";
                $fileName = "advertise_video" . time() . "." . $extension;
                request("media_file")->move($destinationPath, $fileName);
                $input["media_file"] = $fileName;
            } else {
                $input["media_file"] = $Advertisement->media_file;
            }
        }
        //updating advertisement data
        $Advertisement::where("adv_id", $id)->update($input);
        return redirect()
            ->route("admin.advertisementlist")
            ->with("message", " Advertisement Edited successfully!");
    }
    //delete single advertisement
    public function deleteadv($adv_id)
    {
        $Advertisement = Advertisement::where("adv_id", $adv_id)->first();

        if (empty($Advertisement)) {
            return redirect()
                ->route("admin.advertisementlist")
                ->with("message", "No Advertisement Found");
        }
        if ($Advertisement->media_type == "Video") {
            $destinationPath =public_path() ."/uploads/advertisementVideo/" .$Advertisement->media_file;
        } else {
            $destinationPath = public_path() . "/uploads/advertiseImages/" .$Advertisement->media_file;
        }
        // deleting ads files
        if (file_exists($destinationPath))
        @unlink($destinationPath);
        $Advertisement::where("adv_id", $adv_id)->delete();
        return redirect()
            ->route("admin.advertisementlist")
            ->with("message", "Advertisement deleted successfully!");
    }
}
