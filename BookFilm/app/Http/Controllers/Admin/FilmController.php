<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
use Carbon\Carbon;
use File;

class FilmController extends Controller
{
    //
    public function MoviePlay(){
    	$DateNow = Carbon::now('Asia/Ho_Chi_Minh');
    	$query = DB::table('film')
    					->where('ReleaseDate', '<', $DateNow)
                        ->orderBy('ReleaseDate', 'desc')
            			->paginate(10);
        $cate = DB::table('categoryfilm')
        		->join('film', 'film.ID', '=', 'categoryfilm.Film_ID')
        		->join('category', 'category.ID', '=', 'categoryfilm.Category_ID')
        		->select('category.Name','categoryfilm.Film_ID')
        		->get();
        $category = DB::table('category')->get();
        return view('admin.film.movie_play')->with([
    										'query'=> $query,
    										'cate' => $cate,
    										'category' => $category
    									]);
    }

    public function ComingMovie(){
    	$DateNow = Carbon::now('Asia/Ho_Chi_Minh');
    	$query = DB::table('film')
    					->where('ReleaseDate', '>=', $DateNow)
                        ->orderBy('ReleaseDate', 'desc')
            			->paginate(10);
         $cate = DB::table('categoryfilm')
        		->join('film', 'film.ID', '=', 'categoryfilm.Film_ID')
        		->join('category', 'category.ID', '=', 'categoryfilm.Category_ID')
        		->select('category.Name','categoryfilm.Film_ID')
        		->get();
        $category = DB::table('category')->get();
        return view('admin.film.coming_movie')->with([
    										'query'=> $query,
    										'cate' => $cate,
    										'category' => $category
    									]);
    }

    public function Add(){
        return view('admin.film.add');
    }

    public function AddFilm(Request $request){
    	$Name = $request->get("Name");
    	$Director = $request->get("Director");
    	$Actor = $request->get("Actor");
    	$Time = $request->get("Time");
    	$ReleaseDate = $request->get("ReleaseDate");
    	$Country = $request->get("Country");
    	$AgeRestriction = $request->get("AgeRestriction");
    	$Description = $request->get("Description");
    	$Trailer = $request->get("Trailer");
    	$MetaTitle = Str_Metatitle($Name);
    	$hinhanh = "";
    	if ($request->hasFile('Image')){
                $img_hinhanh = $request->file("Image");

                // Th?? m???c upload
                $uploadPath = public_path('assets/images/film/'); // Th?? m???c upload
            
                // B???t ?????u chuy???n file v??o th?? m???c
                $img_hinhanh->move($uploadPath, $img_hinhanh->getClientOriginalName());

                $hinhanh = $img_hinhanh->getClientOriginalName();
            }

    	DB::insert('insert into film 
    		(Name, MetaTitle, Image, Director, Actor, Time, ReleaseDate, Country, Vote, AgeRestriction, Description, Trailer, Status) 
    		values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', 
    		[$Name, $MetaTitle, $hinhanh, $Director, $Actor, $Time, $ReleaseDate, $Country, 4, $AgeRestriction, $Description, $Trailer, 1]);


    	$DateNow = Carbon::now('Asia/Ho_Chi_Minh');
    	if ($ReleaseDate < $DateNow) {
    		# code...
    		Session::flash('message', 'Th??m phim m???i th??nh c??ng.');
    		return redirect('/Admin/Film/MoviePlay');
    	}else{
    		Session::flash('message', 'Th??m phim m???i th??nh c??ng.');
    		return redirect('/Admin/Film/ComingMovie');
    	}
    	

    	
    }

    public function Edit($ID){
    	$film = DB::table('film')->where('ID', $ID)->first();
        return view('admin.film.edit')->with([
    										'film'=> $film
    									]);
    }

    public function EditFilm(Request $request){
    	$ID = $request->get("ID");
    	$Name = $request->get("Name");
    	$Director = $request->get("Director");
    	$Actor = $request->get("Actor");
    	$Time = $request->get("Time");
    	$ReleaseDate = $request->get("ReleaseDate");
    	$Country = $request->get("Country");
    	$AgeRestriction = $request->get("AgeRestriction");
    	$Description = $request->get("Description");
    	$Trailer = $request->get("Trailer");
    	$MetaTitle = Str_Metatitle($Name);
    	$hinhanh = "";
    	if ($request->hasFile('Image')){
    		$img_hinhanh = $request->file("Image");

                // Th?? m???c upload
                $uploadPath = public_path('assets/images/film/'); // Th?? m???c upload

                // B???t ?????u chuy???n file v??o th?? m???c
                $img_hinhanh->move($uploadPath, $img_hinhanh->getClientOriginalName());

                $hinhanh = $img_hinhanh->getClientOriginalName();

                $img = DB::table('film')->where('ID', $ID)->first();
                if($img->Image != $hinhanh){//N???u c?? s???a file ???nh, th?? ti???n h??nh x??a ???nh c?? v?? th??m ???nh m???i
                	File::delete($img->Image);
                    // $img_hinhanh->move($uploadPath, $hinhanh->getClientOriginalName());
                	DB::update('update film set Image = ? where ID = ?', [$hinhanh, $ID]);
                }
            }

    	DB::update('update film set Name = ?, MetaTitle = ?, Director = ?, Actor = ?, Time = ?, ReleaseDate = ?, Country = ?, AgeRestriction = ?, Description = ?, Trailer = ?  where ID = ?', 
                [$Name, $MetaTitle, $Director, $Actor, $Time, $ReleaseDate, $Country, $AgeRestriction, $Description, $Trailer,  $ID]);


    	$DateNow = Carbon::now('Asia/Ho_Chi_Minh');
    	if ($ReleaseDate < $DateNow) {
    		# code...
    		Session::flash('message', 'S???a phim  th??nh c??ng.');
    		return redirect('/Admin/Film/MoviePlay');
    	}else{
    		Session::flash('message', 'S???a phim  th??nh c??ng.');
    		return redirect('/Admin/Film/ComingMovie');
    	}
    }

    //Th??m th??? lo???i cho phim
    public function AddCate($Film_ID, $Category_ID){
    	DB::insert('insert into categoryfilm 
    		(Film_ID, Category_ID) 
    		values (?, ?)', 
    		[
    			$Film_ID, 
    			$Category_ID
    		]);

    	$ID = DB::table('categoryfilm')->max('ID');
    	$cate = DB::table('categoryfilm')
        		->join('film', 'film.ID', '=', 'categoryfilm.Film_ID')
        		->join('category', 'category.ID', '=', 'categoryfilm.Category_ID')
        		->where('categoryfilm.ID', $ID)
        		->select('category.Name','categoryfilm.ID')
        		->first();
    	return response()->json([
    		'cate' => $cate
    	]);
    }

    public function DeleteCate($ID){
    	$category_id = DB::table('categoryfilm')->where("ID", $ID)->first();
    	DB::table('categoryfilm')->where("ID", $ID)->delete();
    	$cate = DB::table('category')->where("ID", $category_id->Category_ID)->first();
    	return response()->json([
    		'cate' => $cate
    	]);
    }

    public function GetCategoryFilmByID($Film_ID)
    {
    	# code...
    	$cate = DB::table('categoryfilm')
        		->join('film', 'film.ID', '=', 'categoryfilm.Film_ID')
        		->join('category', 'category.ID', '=', 'categoryfilm.Category_ID')
        		->where('categoryfilm.Film_ID', $Film_ID)
        		->select('category.Name','categoryfilm.ID', 'categoryfilm.Category_ID')
        		->get();
       	$film = DB::table('film')->where('ID', $Film_ID)->first();
        return response()->json([
    		'cate' => $cate,
    		'film' => $film
    	]);
    }

    public function DeleteFilm($ID){
        $film = DB::table('film')->where('ID', $ID)->first();
        File::delete($film->Image);
        DB::table('film')->where("ID", $ID)->delete();
        return response()->json([
         'success' => 'Record deleted successfully!'
     ]);
    }

    public function ChangeStatus($ID){
    	$film = DB::table('film')->where('ID', $ID)->first();
    	$status = "";
    	if($film->Status == true)
    		$status = false;
    	else
    		$status = true;
    	DB::update('update film set Status = ? where ID = ?', [$status, $ID]);
    	return response()->json([
    		'success' => 'Record deleted successfully!'
    	]);
    }

}

function Str_Metatitle($str) {
    	$str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???)/", 'a', $str);
    	$str = preg_replace("/(??|??|???|???|???|??|???|???|???|???|???)/", 'e', $str);
    	$str = preg_replace("/(??|??|???|???|??)/", 'i', $str);
    	$str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???)/", 'o', $str);
    	$str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???)/", 'u', $str);
    	$str = preg_replace("/(???|??|???|???|???)/", 'y', $str);
    	$str = preg_replace("/(??)/", 'd', $str);

    	$str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???)/", 'a', $str);
    	$str = preg_replace("/(??|??|???|???|???|??|???|???|???|???|???)/", 'e', $str);
    	$str = preg_replace("/(??|??|???|???|??)/", 'i', $str);
    	$str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???|??|???|???|???|???|???)/", 'o', $str);
    	$str = preg_replace("/(??|??|???|???|??|??|???|???|???|???|???)/", 'u', $str);
    	$str = preg_replace("/(???|??|???|???|???)/", 'y', $str);
    	$str = preg_replace("/(??)/", 'd', $str);
    	$str = preg_replace("/(' ')/", '-', $str);
        $str = str_replace(" ","-",trim($str));
    	return $str;
    }

    //Get link 
        function GetLink($str)
        {
            $link = "";
            $dem = 1;
            for($i = count($str) - 1; $i >= 0; $i--)
            {
                if ($dem <= 11)
                {
                    $link += $str[$i];
                    $dem++;
                }
                else
                {
                    break;
                }
            }
            
            return strrev($link); 
        }
?>
