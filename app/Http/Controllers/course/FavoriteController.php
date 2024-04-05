<?php

namespace App\Http\Controllers\course;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', Rule::exists("online_centers", "id")],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "error" => $validator->errors()->all()[0],
                "status" => "failure",
                "message" => "error!"
            ]);
        }
        $favorite = Favorite::where([
            'id_online_center' => $request->id,
            'id_user' => Auth::id()
        ])->first();
        if (!is_null($favorite)) {
            Auth::user()->favorite()->where('id_online_center', $request->id,)->delete();
            //  $favorite->delete();
            return response()->json([
                "message" => "delete  favorite",
                "status" => "success",
            ]);
        } else {
            Favorite::create([
                'id_online_center' => $request->id,
                'id_user' => Auth::id()
            ]);
            return response()->json([
                "message" => "add to favorite",
                "status" => "success",
            ]);
        }
    }

    public function index()
    {

        $fav = Favorite::with("favorites")->whereHas("favorites", function ($q) {
            $q->where("id_user", "=", Auth::id());
        })->get();
//        dd($fav);

        /*  $items = Category::with('items')
        ->where("id",">",0) //this is from Category table
        ->WhereHas('items', function($query)  {
            $query->where("id","3")
            ->where("item_count","0");
            $query->whereHas('fav',function($q){
                $q->where('id_user', 1);
            });
        }) ->dd();*/

        /*  $items = Item::with('category')
        //->where("id",3) //this is from Category table
        ->WhereHas('fav', function($query)  {
            $query->where("id_user",1);
            //->where("item_count","0");

        })->get();*/











        /* $items = Item::all();
        foreach ($items as $item) {
            $x= Favorite::where("id_online_center","=", $item->id)
                ->where("id_user" ,"=", 1)->get();
                if(count($x)>0) {
                    $item->fav=true;
                }else{
                    $item->fav=false;
                }
        }*/





        ///just item in faforite
        /* $q = Item::query();
        $q->whereHas("fav", function ($q) {
            $q->where("id_user", Auth::id());
        })->where("id", "=", 5);
        $items =  $q->with("category")->get();*/
        ////////

        //   just item+catecory+fave in faforite

        /*$q = Category::query();
        $q->with("items");
        $q = $q->whereHas("items", function ($q) {
            $q->where("id","!=",4);
        });*/

        /* $items = Item::with("category")->get();
        foreach ($items as $item) {
            $temp = Favorite::where("id_online_center","=", $item->id)
            ->where("id_user" ,"=", Auth::id())->get();
            if (count($temp) > 0) {
                $item['isfavorite'] = true;
            } else {
                $item["isfavorite"] = false;
            }
        }*/
        /*  $items=DB::table('categories')
        ->join('items', function ($join) {
        $join->on('categories.id', '=', 'items.categories_id')
        ->where('items.item_discount', '!=', 0);
        })->get( );

        foreach ($items as $item) {
            $temp = Favorite::where("id_online_center","=", $item->id)
            ->where("id_user" ,"=", Auth::id())->get();
            if (count($temp) > 0) {
                $item->isfavorite = true;
            } else {
                $item->isfavorite = false;
            }
        }*/
        /* $collection = collect([
             [
                 "fav_id" => 9,
                 "id_online_center" => 5,
                 "id_user" => 3,
                 "created_at" => null,
                 "updated_at" => null,
                 "favorites" => [
                     "id" => 5,
                     "item_name" => "camera1",
                     "item_name_ar" => "fefw",
                     "item_desc_ar" => "werwr",
                     "item_desc" => "werwerwerw",
                     "item_image" => "",
                     "item_count" => 3,
                     "item_price" => 23,
                     "item_active" => 1,
                     "item_discount" => 23,
                     "categories_id" => 3,
                     "created_at" => null,
                     "updated_at" => null
                 ]
             ]
         ]);*/



        $mapped = $fav->map(function ($item, $key) {
            //  $item["item_id"] = $item["favorites"]["id"];
            $item["item_name"] = $item["favorites"]["id"];
            //$item["item_name"] = $item["id"];
//            $item["item_name_ar"] = $item["favorites"]["item_name_ar"];
//            $item["item_desc"] = $item["favorites"]["item_desc"];
//            $item["item_image"] = $item["favorites"]["item_image"];
//            $item["item_count"] = $item["favorites"]["item_count"];
//            $item["item_price"] = $item["favorites"]["item_price"];
//            $item["item_active"] = $item["favorites"]["item_active"];
//            $item["item_discount"] = $item["favorites"]["item_discount"];
//            $item["categories_id"] = $item["favorites"]["categories_id"];
            //unset($item["favorites"]);
            //unset($item["id"]);
         //   unset($item['']);
            return $item;
        });

        return response([
            'item' =>  $mapped,
            "status" => "success",
        ]);
    }
}
