<template>
	<div class="container-fluid" style="height: 100%;">
		<div class="row" style="height: 100%;">
			<div class="col-md-2 h-100" style="background-color: #0069d9;border-color: #0062cc;color:white; height: 100%; padding: 0px;">
				<div style="background-color: #0257b3; padding: 25px 15px;">
					<div style="height: 50px; width: 50px; background-color: #fff; border-radius: 50%; margin-bottom: 15px;"></div>
					<label>{{ loggedInUser }}</label>
				</div>
				<div>
					<div class="list-group">
						<a href="dashboard" class="list-group-item list-group-item-action ">
							Dashboard
						</a>
						<a href="" class="list-group-item list-group-item-action disabled">
							User Management
						</a>
						<a href="user-management-role" class="list-group-item list-group-item-action" style="padding-left: 45px;">
							Roles
						</a>
						<a href="user-management-user" class="list-group-item list-group-item-action " style="padding-left: 45px;">
							Users
						</a>
						<a href="#" class="list-group-item list-group-item-action disabled">
							Expense Management
						</a>
						<a href="expense-category" class="list-group-item list-group-item-action active" style="padding-left: 45px;">
							Expense Categories
						</a>
						<a href="expenses" class="list-group-item list-group-item-action " style="padding-left: 45px;">
							Expenses
						</a>
					</div>
				</div>
			</div>
			<div class="col-md-10" style="padding: 0px;">
				<nav class="navbar navbar-expand-lg navbar-light bg-light">
					<div class="collapse navbar-collapse" id="navbarText">
						<ul class="navbar-nav" style="margin-left: auto !important;">
							<li class="nav-item">
								<a class="nav-link disabled" style="padding-right: 25px;">Welcome to Expense Manager</a>
							</li>
							<li class="nav-item ">
								<a class="nav-link" style="padding-right: 25px; cursor: pointer;" v-bind:style="styles" @click='showChangePassword'>Change Password</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="logout">Logout</a>
							</li>
						</ul>
					</div>
				</nav>
				<br>
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-10 offset-md-1" v-bind:style="categoryAdminStyles">
							<div class="row">
								<div class="col-md-6">
									<h4>Expense Categories</h4>
								</div>
								<div class="col-md-6" style="text-align:right">
									<h5>Expense Management > Expense Categories</h5>
								</div>
							</div>
							
							<br>
							<br>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-hover">
										<thead>
											<tr>
												<th scope="col">Display Name</th>
												<th scope="col">Description</th>
												<th scope="col">Created at</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="category in categories" @click="viewCategory(category)">
												<td>{{category.display_name}}</td>
												<td>{{category.description}}</td>
												<td>{{category.created_at}}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2 offset-md-10">
									  <b-button variant="info" class="float-right btn-block" @click="addCategory">Add Category</b-button>
								</div>
							</div>
						</div>
					</div>
				</div>			
			</div>
		</div>

		<b-modal ref="updateCategoryModal" hide-footer title="Update Category">
			<form ref="form">
				<b-form-group :state="nameState" label="Category Name" label-for="categoryName-input" invalid-feedback="Category name is required" >
					<b-form-input id="categoryName-input" v-model="categoryForm.categoryName" :state="nameState" required>
					</b-form-input>
				</b-form-group>
				<b-form-group :state="descState" label="Description" label-for="description-input" invalid-feedback="Description is required" >
					<b-form-input id="description-input" v-model="categoryForm.description" :state="descState" required>
					</b-form-input>
				</b-form-group>
			</form>
			<hr>
			<b-row>
				<b-col>
					<b-row>
						<b-col>
							<b-button block variant="danger" @click="deleteModal">Delete</b-button>
						</b-col>
						<b-col></b-col>
					</b-row>
				</b-col>
				<b-col>
					<b-row>
						<b-col>
							<b-button block @click="hideModal">Cancel</b-button>
						</b-col>
						<b-col>
							<b-button block variant="primary" @click="updateCategory">Update</b-button>
						</b-col>
					</b-row>
				</b-col>
			</b-row>
		</b-modal>
		<b-modal ref="deleteCategoryModal" hide-footer title="Delete Category" id="deleteCategoryModal">
			<label>Are you sure you want to delete this category? Click delete to continue</label>
			<hr>
			<b-row>
				<b-col>
					<b-row>
						<b-col>
							<b-button block @click="$bvModal.hide('deleteCategoryModal')">Cancel</b-button>
						</b-col>
						<b-col>
							<b-button block variant="danger" @click="deleteCategory">Delete</b-button>
						</b-col>
					</b-row>
				</b-col>
			</b-row>
		</b-modal>
		<b-modal ref="addCategoryModal" hide-footer title="Add Category">
			<form ref="form">
				<b-form-group :state="nameState" label="Category Name" label-for="categoryName-input" invalid-feedback="Category name is required" >
					<b-form-input id="categoryName-input" v-model="addCategoryForm.categoryName" :state="nameState" required>
					</b-form-input>
				</b-form-group>
				<b-form-group :state="descState" label="Description" label-for="description-input" invalid-feedback="Description is required" >
					<b-form-input id="description-input" v-model="addCategoryForm.description" :state="descState" required>
					</b-form-input>
				</b-form-group>
			</form>
			<hr>
			<b-row>
				<b-col>
					<b-row>
						<b-col>
							<b-button block @click="hideModal">Cancel</b-button>
						</b-col>
						<b-col>
							<b-button block variant="primary" @click="addCategoryModal">Save</b-button>
						</b-col>
					</b-row>
				</b-col>
			</b-row>
		</b-modal>
		<b-modal ref="changePasswordModal" hide-footer title="Change Password">
			<form ref="form">
				<b-form-group :state="oldPassState" label="Old Password" label-for="oldPass-input" invalid-feedback="Old password is required" >
					<b-input type="password" id="oldPass-input" aria-describedby="password-help-block" v-model="passwordForm.oldPass" :state="oldPassState" required></b-input>
				</b-form-group>
				<b-form-group :state="newPassState" label="New Password" label-for="newPass-input" invalid-feedback="New password is required" >
					<b-input type="password" id="newPass-input" aria-describedby="password-help-block" v-model="passwordForm.newPass" :state="newPassState" required></b-input>
				</b-form-group>
				<b-form-group :state="confirmedPassState" label="Confirmed Password" label-for="confirmedPass-input" invalid-feedback="Confirmed password is required" >
					<b-input type="password" id="confirmedPass-input" aria-describedby="password-help-block" v-model="passwordForm.confirmedPass" :state="confirmedPassState" required></b-input>
				</b-form-group>
			</form>
			<hr>
			<b-row>
				<b-col>
					<b-button block @click="hideModal">Cancel</b-button>
				</b-col>
				<b-col>
					<b-button block variant="primary" @click="showConfirmModal">Update</b-button>
				</b-col>
			</b-row>
		</b-modal>
		<b-modal ref="confirmChangeModal" hide-footer title="Confirm">
			<p>Your account will be logged out. Are you sure you want to change your password?</p>
			<hr>
			<b-row>
				<b-col>
					<b-button block @click="hideModal">Cancel</b-button>
				</b-col>
				<b-col>
					<b-button block variant="primary" @click="updatePassword">Update</b-button>
				</b-col>
			</b-row>
		</b-modal>
	</div>
</template>
<script>
export default {
	data(){
		return{
			categoryForm :{
				categoryName: '',
				description: '',
				key : ''
			},
			addCategoryForm:{
				categoryName: '',
				description: ''
			},
			passwordForm :{
				oldPass: '',
				newPass: '',
				confirmedPass: ''
			},
			categories : null,			
			nameState: null,			
			descState: null,
			oldPassState: null,			
			newPassState: null,			
			confirmedPassState: null,

			styles: '',
			categoryAdminStyles: '',
			loggedInUser: ''
		}
	},
	created(){
		this.setupCategories()
	},
	methods:{
		setupCategories(){
			axios.post('getexpensecategorydetails')
			.then(response=>{
				this.loggedInUser = response.data.loggedInUser
				if(response.data.role == 1){
					this.styles = { 'display': 'none' }
					this.categoryAdminStyles = ''
				}
				else{
					this.styles = ''
					this.categoryAdminStyles = { 'display': 'none' }
					alert("You don't have any access here!")
					window.location.href = 'dashboard'
				}
				
				if(response.data.status_code == 0){
					alert("There was an error. Please check the following:\n\n*   " + response.data.msg)
				}
				else if(response.data.status_code == 1){
					this.categories = response.data.msg
				}
				else{
					alert(response.data.msg)
				}

			})
		},
		viewCategory(categories){
			this.$refs['updateCategoryModal'].show()
			this.categoryForm.categoryName = categories.display_name
			this.categoryForm.description = categories.description
			this.categoryForm.key = categories.id
		},
		updateCategory() {
			let vm = this
			axios.post('validateupdatecategory', vm.categoryForm)
            .then(function (response) {
            	if(response.data.status_code == 0){
            		alert(response.data.msg)
            	}
            	else if(response.data.status_code == 1){
            		alert('Update Successfully')
            		window.location.href = 'expense-category'
            	}
            	else{
            		alert(response.data.msg)
            	}
            });	
	        this.$refs['updateCategoryModal'].hide()
		},
		deleteModal(){
			this.$refs['deleteCategoryModal'].show()
		},
	    deleteCategory(e){
	    	let vm = this
			axios.post('validatedeletecategory', vm.categoryForm)
            .then(function (response) {
            	if(response.data.status_code == 0){
            		alert(response.data.msg)
            	}
            	else if(response.data.status_code == 1){
            		alert('Delete Successfully')
            		window.location.href = 'expense-category'
            	}
            	else{
            		alert(response.data.msg)
            	}
            });	
	        this.$refs['deleteCategoryModal'].hide()
	    },
		hideModal() {
			this.$refs['updateCategoryModal'].hide()
			this.$refs['deleteCategoryModal'].hide()
			this.$refs['addCategoryModal'].hide()
			this.$refs['changePasswordModal'].hide()
			this.$refs['confirmChangeModal'].hide()

			let vm = this
			vm.categoryForm.categoryName = ''
			vm.categoryForm.description = ''
			vm.categoryForm.key = ''

			vm.addCategoryForm.categoryName = ''
			vm.addCategoryForm.description = ''

			vm.passwordForm.oldPass = ''
			vm.passwordForm.newPass = ''
			vm.passwordForm.confirmedPass = ''
	
			vm.nameState = null
			vm.descState = null
			vm.oldPassState = null
			vm.newPassState = null
			vm.confirmedPassState = null

		},
		addCategory() {
			this.$refs['addCategoryModal'].show()
		},
		addCategoryModal() {
			let vm = this
			axios.post('validateaddcategory', vm.addCategoryForm)
            .then(function (response) {
            	if(response.data.status_code == 0){
            		alert(response.data.msg)
            	}
            	else if(response.data.status_code == 1){
            		alert('New category is added successfully')
            		window.location.href = 'expense-category'
            	}
            	else{
            		alert(response.data.msg)
            	}
            });	
	        this.$refs['updateCategoryModal'].hide()
		},
		showChangePassword(){
			this.$refs['changePasswordModal'].show()
		},
		showConfirmModal(){
			this.$refs['confirmChangeModal'].show()
		},
		updatePassword(){
			let vm = this
			axios.post('validatepassword', vm.passwordForm)
			.then(response=>{
				this.$refs['confirmChangeModal'].hide()
				if(response.data.status_code == 0){
					alert("There was an error. Please check the following:\n\n*   " + response.data.msg)
				}
				else if(response.data.status_code == 1){
					alert('Update Successfully')
        			window.location.href = 'logout'
				}
				else{
					alert(response.data.msg)
				}

			})
		}
	}
}
</script>