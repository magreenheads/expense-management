<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Validator;
use DB;

use App\Expense_Category;
use App\Expenses;
use App\Role;
use App\User;

class indexController extends Controller
{
	//LOGIN
	public function index(){
		if(!Session::has("userCredits")){
			return redirect("/login");
		}
		return redirect('/dashboard');
	}
	public function dashboard(){
		if(!Session::has("userCredits")){
			return redirect("/login");
		}
		return view("pages.index");
	}
	public function logout(){
		Session::flush();
		return redirect("/login");
	}
	public function login(){
		if(Session::has("userCredits")){
			return redirect("/dashboard");
		}
		return view('pages.login');
	}
	public function validatelogin(Request $_request){
		try{
			$_requestValue = $_request->all();

			$_validator = Validator::make(
				[
				"email" => trim($_requestValue["email"]),
				"password" => trim($_requestValue["password"])
				],
				[
				"email" => "required|email",
				"password" => "required"
				]
				);

			if ($_validator->fails()) {
				$_errMsgs = $_validator->messages();
				return json_encode(array("status_code" => 0, "msg" => implode("\n*   ", json_decode(json_encode($_errMsgs->all()), true))));
			}

			$_user = DB::table("users")
			->select("users.user_id", "users.user_name", "users.password", "users.email", "users.role_id", "users.created_at", "roles.display_name as role")
			->join('roles', 'users.role_id', '=', 'roles.role_id')
			->where("email", pg_escape_string(trim($_requestValue["email"])))
			->where("password", pg_escape_string(trim($_requestValue["password"])))
			->get();

			if(sizeof($_user) > 0){
				$_userDtls = json_decode(json_encode($_user), true);
				Session::put("userCredits", $_userDtls);
				return json_encode(array("status_code" => 1, "msg" => "success"));
			}
			else{
				return json_encode(array("status_code" => -1, "msg" => "No record found!"));
			}
		}catch(Exception $_ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!"));
		}
	}
    //DASHBOARD
	public function getExpenseCategory(){
		try{
			if(!Session::has("userCredits")){
				return redirect("/login");
			}
			$expenseCategory = json_decode(json_encode(Expense_Category::select('category_id', 'display_name as expense_category')->get()), true);
			$returnArrayValues = array();

			for($ctr = 0; $ctr < sizeof($expenseCategory); $ctr++){
				$totalExpenses = Expenses::where("category_id", $expenseCategory[$ctr]["category_id"])->sum("amount");
				
				array_push($returnArrayValues, array("category_name" => $expenseCategory[$ctr]["expense_category"], "total" => $totalExpenses));
			}
			if(empty($returnArrayValues)){	

				return json_encode(array("status_code" => 1, "msg" => array(array("category_name" => "No result found", "total" => "")), "role" => Session::get("userCredits")[0]["role_id"], "loggedInUser" => Session::get("userCredits")[0]["user_name"]."(".Session::get("userCredits")[0]["role"].")"));
			} 

			return json_encode(array("status_code" => 1, "msg" => $returnArrayValues, "role" => Session::get("userCredits")[0]["role_id"], "loggedInUser" => Session::get("userCredits")[0]["user_name"]."(".Session::get("userCredits")[0]["role"].")"));
		}catch(Exception $ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!", "role" => Session::get("userCredits")[0]["role_id"], "loggedInUser" => Session::get("userCredits")[0]["user_name"]."(".Session::get("userCredits")[0]["role"].")"));
		}
	}
    //ROLE
	public function role(){
		if(!Session::has("userCredits")){
			return redirect("/login");
		}
		return view('pages.role');	    
	}
	public function getuserrole(){
		try{
			if(!Session::has("userCredits")){
				return redirect("/login");
			}
			$_roles = json_decode(json_encode(Role::all()), true);
			$returnArrayValues = array();
			for($ctr = 0; $ctr < sizeof($_roles); $ctr++){		
				array_push($returnArrayValues, array("id"=>$_roles[$ctr]["role_id"],"display_name" => $_roles[$ctr]["display_name"], "description" => $_roles[$ctr]["description"],'created_at' => $_roles[$ctr]["created_at"]));
			}
			if(empty($returnArrayValues)){
				return json_encode(array("status_code" => 1, "msg" => array(array("id"=>"","display_name" => "No result found", "description" => "", "created_at" => "")), "role" => Session::get("userCredits")[0]["role_id"], "loggedInUser" => Session::get("userCredits")[0]["user_name"]."(".Session::get("userCredits")[0]["role"].")"));
			}
			return json_encode(array("status_code" => 1, "msg" => $returnArrayValues, "role" => Session::get("userCredits")[0]["role_id"], "loggedInUser" => Session::get("userCredits")[0]["user_name"]."(".Session::get("userCredits")[0]["role"].")"));
		}catch(Exception $ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!", "role" => Session::get("userCredits")[0]["role_id"], "loggedInUser" => Session::get("userCredits")[0]["user_name"]."(".Session::get("userCredits")[0]["role"].")"));
		}
	}
	public function validateupdaterole(Request $_request){
		try{
			if(!Session::has("userCredits")){
				return redirect("/login");
			}
			$_requestValue = $_request->all();
			$_validator = Validator::make(
				[
				"key" => trim($_requestValue["key"]),
				"role" => trim($_requestValue["roleName"])
				],
				[
				"key" => "required|numeric",
				"role" => "required"
				]
				);

			if ($_validator->fails()) {
				$_errMsgs = $_validator->messages();
				return json_encode(array("status_code" => -1, "msg" => implode("\n*   ", json_decode(json_encode($_errMsgs->all()), true))));
			}

			if($_requestValue["key"] === 1 ){
				return json_encode(array("status_code" => 0, "msg" => "You can't update administrator role."));
			}
			else{
				Role::where("role_id", intval($_requestValue["key"]))->update(['display_name' => pg_escape_string(trim($_requestValue["roleName"])), 'description' => pg_escape_string(trim($_requestValue["description"]))]);
				return json_encode(array("status_code" => 1, "msg" => "success"));
			}
		}catch(Exception $ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!"));
		}
	}
	public function deleterole(Request $_request){
		try{
			if(!Session::has("userCredits")){
				return redirect("/login");
			}

			$_requestValue = $_request->all();
			if($_requestValue["key"] === 1 ){
				return json_encode(array("status_code" => 0, "msg" => "You can't delete administrator role."));
			}
			else{
				$_deleteRole = Role::where("role_id", intval($_requestValue["key"]))->delete();
				if($_deleteRole > 0){
					return json_encode(array("status_code" => 1, "msg" => "success"));
				}
				return json_encode(array("status_code" => 0, "msg" => "No role found!"));
			}
		}catch(Exception $ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!"));
		}
	}
	public function addrole(Request $_request){
		try{
			if(!Session::has("userCredits")){
				return redirect("/login");
			}
			$_requestValue = $_request->all();
			$_validator = Validator::make(
				[
				"role" => trim($_requestValue["roleName"])
				],
				[
				"role" => "required"
				]
				);

			if ($_validator->fails()) {
				$_errMsgs = $_validator->messages();
				return json_encode(array("status_code" => -1, "msg" => implode("\n*   ", json_decode(json_encode($_errMsgs->all()), true))));
			}
			
			Role::insert(['display_name' => pg_escape_string(trim($_requestValue["roleName"])), 'description' => pg_escape_string(trim($_requestValue["description"])), "created_at" => date("Y-m-d H:i:s")]);
			return json_encode(array("status_code" => 1, "msg" => "success"));

		}catch(Exception $ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!"));
		}
	}
    //USER
	public function user(){
		if(!Session::has("userCredits")){
			return redirect("/login");
		}
		return view('pages.user');	    
	}
	public function getuserdetails(Request $_request){
		try{
			if(!Session::has("userCredits")){
				return redirect("/login");
			}
			$_users = DB::table("users")
			->select("users.user_id", "users.user_name", "users.password", "users.email", "users.role_id", "users.created_at", "roles.display_name as role")
			->join('roles', 'users.role_id', '=', 'roles.role_id')
			->get();
			$_users = json_decode(json_encode($_users), true);
			
			$returnArrayValues = array();
			for($ctr = 0; $ctr < sizeof($_users); $ctr++){		
				array_push($returnArrayValues, array("id"=>$_users[$ctr]["user_id"], "name" => $_users[$ctr]["user_name"], "email" => $_users[$ctr]["email"], "role" => $_users[$ctr]["role"], 'created_at' => $_users[$ctr]["created_at"], "role_id" => $_users[$ctr]["role_id"]));
			}
			if(empty($returnArrayValues)){
				return json_encode(array("status_code" => 1, "msg" => array(array("id"=>"", "name" => "No result found", "email" => "", "role" => $_users[$ctr]["role"], "created_at" => "")), "role" => Session::get("userCredits")[0]["role_id"], "loggedInUser" => Session::get("userCredits")[0]["user_name"]."(".Session::get("userCredits")[0]["role"].")"));
			}
			return json_encode(array("status_code" => 1, "msg" => $returnArrayValues, "role" => Session::get("userCredits")[0]["role_id"], "loggedInUser" => Session::get("userCredits")[0]["user_name"]."(".Session::get("userCredits")[0]["role"].")"));
		}catch(Exception $ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!", "role" => Session::get("userCredits")[0]["role_id"], "loggedInUser" => Session::get("userCredits")[0]["user_name"]."(".Session::get("userCredits")[0]["role"].")"));
		}
	}
	public function validateupdateuser(Request $_request){
		try{
			if(!Session::has("userCredits")){
				return redirect("/login");
			}
			$_requestValue = $_request->all();

			$_validator = Validator::make(
				[
				"key" => trim($_requestValue["key"]),
				"name" => trim($_requestValue["userName"]),
				"email" => trim($_requestValue["email"]),
				"role_id" => trim($_requestValue["role_id"])
				],
				[
				"key" => "required|numeric",
				"name" => "required",
				"email" => "required|email",
				"role_id" => "required|numeric"
				]
				);

			if ($_validator->fails()) {
				$_errMsgs = $_validator->messages();
				return json_encode(array("status_code" => -1, "msg" => implode("\n*   ", json_decode(json_encode($_errMsgs->all()), true))));
			}

			if($_requestValue["key"] === 1 ){
				return json_encode(array("status_code" => 0, "msg" => "You can't update administrator account."));
			}
			else{
				User::where("user_id", intval($_requestValue["key"]))->update(['user_name' => pg_escape_string(trim($_requestValue["userName"])), 'email' => pg_escape_string(trim($_requestValue["email"])), 'role_id' => $_requestValue["role_id"]]);
				return json_encode(array("status_code" => 1, "msg" => "success"));
			}
		}catch(Exception $ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!"));
		}
	}
	public function displayuserrole(){
		try{
			if(!Session::has("userCredits")){
				return redirect("/login");
			}
			$_roles = json_decode(json_encode(Role::all()), true);
			$returnArrayValues = array();
			for($ctr = 0; $ctr < sizeof($_roles); $ctr++){		
				array_push($returnArrayValues, array('id'=>$_roles[$ctr]["role_id"],'name'=>$_roles[$ctr]["display_name"]));
			}
			return json_encode(array("status_code" => 1, "msg" => $returnArrayValues));
		}catch(Exception $ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!"));
		}
	}
	public function deleteuser(Request $_request){
		try{
			$_requestValue = $_request->all();
			$_validator = Validator::make(
				[
				"id" => trim($_requestValue["key"])
				],
				[
				"id" => "required|numeric"
				]
				);

			if ($_validator->fails()) {
				$_errMsgs = $_validator->messages();
				return json_encode(array("status_code" => -1, "msg" => implode("\n*   ", json_decode(json_encode($_errMsgs->all()), true))));
			}

			if(intval($_requestValue["key"]) === Session::get("userCredits")[0]["user_id"]){
				return json_encode(array("status_code" => 0, "msg" => "You can't delete your own account."));
			}
			else{
				$_deleteUser = User::where("user_id", intval($_requestValue["key"]))->delete();

				if($_deleteUser > 0){
					return json_encode(array("status_code" => 1, "msg" => "success"));
				}
				return json_encode(array("status_code" => 0, "msg" => "No role found!"));
			}
		}catch(Exception $ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!"));
		}
	}
	public function adduser(Request $_request){
		try{
			$_requestValue = $_request->all();
			$_validator = Validator::make(
				[
				"name" => trim($_requestValue["userName"]),
				"email" => trim($_requestValue["email"]),
				"role_id" => trim($_requestValue["role_id"])
				],
				[
				"name" => "required",
				"email" => "required|email",
				"role_id" => "required|numeric"
				]
				);

			if ($_validator->fails()) {
				$_errMsgs = $_validator->messages();
				return json_encode(array("status_code" => -1, "msg" => implode("\n*   ", json_decode(json_encode($_errMsgs->all()), true))));
			}

			$_validateUser = User::whereRaw("LOWER(email) = '" .pg_escape_string(trim($_requestValue["email"])) . "'")->get();
			
			if(sizeof($_validateUser) > 0){
				return json_encode(array("status_code" => 0, "msg" => "User email address already exist!"));
			}

			User::insert(["user_name" => pg_escape_string(trim($_requestValue["userName"])), "email" => pg_escape_string(trim($_requestValue["email"])), "role_id" => $_requestValue["role_id"], "password" => pg_escape_string("12345"), "created_at" => date("Y-m-d H:i:s")]);
			
			return json_encode(array("status_code" => 1, "msg" => "success"));
		}catch(Exception $ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!"));
		}
	}
    //EXPENSE CATEGORY
	public function getexpensecategorydetails(){
		if(!Session::has("userCredits")){
			return redirect("/login");
		}
		return view('pages.expense_category');	 
	}
	public function getexpensecategories(){
		try{
			$categories = json_decode(json_encode(Expense_Category::all()), true);
			$returnArrayValues = array();
			for($ctr = 0; $ctr < sizeof($categories); $ctr++){		
				array_push($returnArrayValues, array("id"=>$categories[$ctr]["category_id"],"display_name" => $categories[$ctr]["display_name"], "description" => $categories[$ctr]["description"],'created_at' => $categories[$ctr]["created_at"]));
			}
			if(empty($returnArrayValues)){
				return json_encode(array("status_code" => 1, "msg" => array(array("id"=>"","display_name" => "No result found", "description" => "", "created_at" => "")), "role" => Session::get("userCredits")[0]["role_id"], "loggedInUser" => Session::get("userCredits")[0]["user_name"]."(".Session::get("userCredits")[0]["role"].")"));
			}
			return json_encode(array("status_code" => 1, "msg" => $returnArrayValues, "role" => Session::get("userCredits")[0]["role_id"], "loggedInUser" => Session::get("userCredits")[0]["user_name"]."(".Session::get("userCredits")[0]["role"].")"));
		}catch(Exception $ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!", "role" => Session::get("userCredits")[0]["role_id"], "loggedInUser" => Session::get("userCredits")[0]["user_name"]."(".Session::get("userCredits")[0]["role"].")"));
		}
	}
	public function validateupdatecategory(Request $_request){
		try{
			$_requestValue = $_request->all();
			$_validator = Validator::make(
				[
				"key" => trim($_requestValue["key"]),
				"category_name" => trim($_requestValue["categoryName"])
				],
				[
				"key" => "required|numeric",
				"category_name" => "required"
				]
				);

			if ($_validator->fails()) {
				$_errMsgs = $_validator->messages();
				return json_encode(array("status_code" => -1, "msg" => implode("\n*   ", json_decode(json_encode($_errMsgs->all()), true))));
			}

			Expense_Category::where("category_id", intval($_requestValue["key"]))->update(['display_name' => pg_escape_string(trim($_requestValue["categoryName"])), 'description' => pg_escape_string(trim($_requestValue["description"]))]);
			return json_encode(array("status_code" => 1, "msg" => "success"));
		}catch(Exception $ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!"));
		}
	}
	public function validateaddcategory(Request $_request){
		try{
			$_requestValue = $_request->all();
			$_validator = Validator::make(
				[
				"category_name" => trim($_requestValue["categoryName"]),
				"description" => trim($_requestValue["description"])
				],
				[
				"category_name" => "required",
				"description" => "required"
				]
				);

			if ($_validator->fails()) {
				$_errMsgs = $_validator->messages();
				return json_encode(array("status_code" => -1, "msg" => implode("\n*   ", json_decode(json_encode($_errMsgs->all()), true))));
			}

			Expense_Category::insert(['display_name' => pg_escape_string(trim($_requestValue["categoryName"])), 'description' => pg_escape_string(trim($_requestValue["description"])), "created_at" => date("Y-m-d H:i:s")]);
			return json_encode(array("status_code" => 1, "msg" => "success"));
		}catch(Exception $ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!"));
		}
	}
	public function validatedeletecategory(Request $_request){
		try{
			$_requestValue = $_request->all();
			$_validator = Validator::make(
				[
				"key" => trim($_requestValue["key"])
				],
				[
				"key" => "required|numeric"
				]
				);

			if ($_validator->fails()) {
				$_errMsgs = $_validator->messages();
				return json_encode(array("status_code" => -1, "msg" => implode("\n*   ", json_decode(json_encode($_errMsgs->all()), true))));
			}

			Expense_Category::where("category_id", intval($_requestValue["key"]))->delete();
			return json_encode(array("status_code" => 1, "msg" => "success"));
		}catch(Exception $ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!"));
		}
	}
    //EXPENSES
	public function getexpenses(){
		if(!Session::has("userCredits")){
			return redirect("/login");
		}
		return view('pages.expenses');	 
	}
	public function getexpensedetails(Request $_request){
		try{
			$_expenses = DB::table("expenses")
			->select("expenses.expenses_id", "expenses.category_id", "expenses.amount", "expenses.entry_date", "expenses.created_at", "expense_categories.display_name as expense_category")
			->join('expense_categories', 'expenses.category_id', '=', 'expense_categories.category_id')
			->get();
			$_expenses = json_decode(json_encode($_expenses), true);
			$returnArrayValues = array();
			$_slctOptionRes = json_decode(json_encode(Expense_Category::all()), true);
			$returnArrayCat = array();
			for ($_ctr=0; $_ctr < sizeof($_expenses); $_ctr++) {
				array_push($returnArrayValues, array("id"=>$_expenses[$_ctr]["expenses_id"],"display_name" => $_expenses[$_ctr]["expense_category"], "entry_date" => $_expenses[$_ctr]["entry_date"],'created_at' => $_expenses[$_ctr]["created_at"], 'amount' => $_expenses[$_ctr]["amount"]));
			}
			for ($_ctr=0; $_ctr < sizeof($_slctOptionRes); $_ctr++) {
				array_push($returnArrayCat, array('id'=>$_slctOptionRes[$_ctr]["category_id"],'name'=>$_slctOptionRes[$_ctr]["display_name"]));
			}
			return json_encode(array("status_code" => 1, "msg" => $returnArrayValues, "cat" => $returnArrayCat, "role" => Session::get("userCredits")[0]["role_id"], "loggedInUser" => Session::get("userCredits")[0]["user_name"]."(".Session::get("userCredits")[0]["role"].")"));
		}catch(Exception $ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!", "role" => Session::get("userCredits")[0]["role_id"], "loggedInUser" => Session::get("userCredits")[0]["user_name"]."(".Session::get("userCredits")[0]["role"].")"));
		}
	}
	public function validateupdateexpenses(Request $_request){
		try{
			$_requestValue = $_request->all();
			$_validator = Validator::make(
				[
				"key" => trim($_requestValue["key"]),
				"Expense Category" => trim($_requestValue["expensecat"]),
				"Amount" => trim($_requestValue["amount"]),
				"Entry Date" => trim($_requestValue["entry_date"])
				],
				[
				"key" => "required|numeric",
				"Expense Category" => "required|numeric",
				"Amount" => "required|numeric",
				"Entry Date" => "required|date"
				]
				);
			if ($_validator->fails()) {
				$_errMsgs = $_validator->messages();
				return json_encode(array("status_code" => -1, "msg" => implode("\n*   ", json_decode(json_encode($_errMsgs->all()), true))));
			}
			Expenses::where("expenses_id", intval($_requestValue["key"]))->update([
				'category_id' => pg_escape_string(trim($_requestValue["expensecat"])), 
				"amount" => $_requestValue["amount"], 
				"entry_date" => pg_escape_string(trim($_requestValue["entry_date"]))]);
			return json_encode(array("status_code" => 1, "msg" => "success"));
		}catch(Exception $ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!"));
		}
	}
	public function validatedeleteexpenses(Request $_request){
		try{
			$_requestValue = $_request->all();
			$_validator = Validator::make(
				[
				"key" => trim($_requestValue["key"])
				],
				[
				"key" => "required|numeric"
				]
				);

			if ($_validator->fails()) {
				$_errMsgs = $_validator->messages();
				return json_encode(array("status_code" => -1, "msg" => implode("\n*   ",json_decode(json_encode($_errMsgs->all()), true))));
			}

			$_deleteExpenses = Expenses::where("expenses_id", intval($_requestValue["key"]))->delete();

			if($_deleteExpenses > 0){
				return json_encode(array("status_code" => 1, "msg" => "success"));
			}
			return json_encode(array("status_code" => 0, "msg" => "No role found!"));
		}catch(Exception $ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!"));
		}
	}
	public function validateaddexpenses(Request $_request){
		try{
			$_requestValue = $_request->all();
			$_validator = Validator::make(
				[
				"Expense Category" => trim($_requestValue["expensecat"]),
				"Amount" => trim($_requestValue["amount"]),
				"Entry Date" => trim($_requestValue["entry_date"])
				],
				[
				"Expense Category" => "required|numeric",
				"Amount" => "required|numeric",
				"Entry Date" => "required|date"
				]
				);

			if ($_validator->fails()) {
				$_errMsgs = $_validator->messages();
				return json_encode(array("status_code" => -1, "msg" => implode("\n*   ",json_decode(json_encode($_errMsgs->all()), true))));
			}

			Expenses::insert(['category_id' => pg_escape_string(trim($_requestValue["expensecat"])), "amount" => $_requestValue["amount"], "entry_date" => pg_escape_string(trim($_requestValue["entry_date"])),  "created_at" => date("Y-m-d H:i:s")]);
			return json_encode(array("status_code" => 1, "msg" => "success"));
		}catch(Exception $ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!"));
		}
	}
	//CHANGE PASSWORD
	public function validatepassword(Request $_request){
		try{
			$_requestValue = $_request->all();
			$_validator = Validator::make(
				[
				"old password" => trim($_requestValue["oldPass"]),
				"new password" => trim($_requestValue["newPass"]),
				"confirm password" => trim($_requestValue["confirmedPass"])
				],
				[
				"old password" => "required",
				"new password" => "required",
				"confirm password" => "required"
				]
				);

			if ($_validator->fails()) {
				$_errMsgs = $_validator->messages();
				return json_encode(array("status_code" => -1, "msg" => implode("\n*   ", json_decode(json_encode($_errMsgs->all()), true))));
			}

			$_user = json_decode(json_encode(User::where("user_id", Session::get("userCredits")[0]["user_id"])->where("password", pg_escape_string(trim($_requestValue["oldPass"])))->get()), true);

			if(sizeof($_user) > 0){
				if(trim($_requestValue["newPass"]) === trim($_requestValue["confirmedPass"])){
					User::where("user_id", pg_escape_string(Session::get("userCredits")[0]["user_id"]))->update(['password' => pg_escape_string(trim($_requestValue["newPass"]))]);
					return json_encode(array("status_code" => 1, "msg" => "success"));
				}
				else{
					return json_encode(array("status_code" => 0, "msg" => "New password didn't match."));
				}
			}
			else{
				return json_encode(array("status_code" => 0, "msg" => "Incorrect old password."));
			}
		}catch(Exception $ex){
			return json_encode(array("status_code" => -1, "msg" => "Exception Error!"));
		}
	}
}
