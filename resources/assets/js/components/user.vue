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
						<a href="user-management-role" class="list-group-item list-group-item-action " style="padding-left: 45px;">
							Roles
						</a>
						<a href="" class="list-group-item list-group-item-action active" style="padding-left: 45px;">
							Users
						</a>
						<a href="#" class="list-group-item list-group-item-action disabled">
							Expense Management
						</a>
						<a href="expense-category" class="list-group-item list-group-item-action " style="padding-left: 45px;">
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
						<div class="col-md-10 offset-md-1" v-bind:style="userAdminStyles">
							<div class="row">
								<div class="col-md-6">
									<h4>User</h4>
								</div>
								<div class="col-md-6" style="text-align:right">
									<h5>User Management > User</h5>
								</div>
							</div>
							
							<br>
							<br>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-hover">
										<thead>
											<tr>
												<th scope="col">Name</th>
												<th scope="col">Email Address</th>
												<th scope="col">Role</th>
												<th scope="col">Created at</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="user in users" @click="viewUser(user)">
												<td>{{user.name}}</td>
												<td>{{user.email}}</td>
												<td>{{user.role}}</td>
												<td>{{user.created_at}}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2 offset-md-10">
									  <b-button variant="info" class="float-right btn-block" @click="showAddUser">Add User</b-button>
								</div>
							</div>
						</div>
					</div>
				</div>			
			</div>
		</div>
		<b-modal ref="updateUserModal" hide-footer title="Update User">
			<form ref="form">
				<b-form-group :state="nameState" label="Name" label-for="userName-input" invalid-feedback="Name is required" >
					<b-form-input id="userName-input" v-model="userForm.userName" :state="nameState" required>
					</b-form-input>
				</b-form-group>
				<b-form-group :state="emailState" label="Email Address" label-for="email-input" invalid-feedback="Email address is required" >
					<b-form-input id="email-input" v-model="userForm.email" :state="emailState" required>
					</b-form-input>
				</b-form-group>
				<b-form-group :state="roleState" label="Role" label-for="role-input" invalid-feedback="role is required" >
					<select class="form-control" :required="true" @change="onChangeSelect">
					<option v-for="role in roleValue" :value="role.id" :selected="userForm.role_id === role.id">
						{{ role.name }}
					</option>
					</select>
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
							<b-button block variant="primary" @click="updateUser">Update</b-button>
						</b-col>
					</b-row>
				</b-col>
			</b-row>
		</b-modal>
		<b-modal ref="addUserModal" hide-footer title="Add User">
			<form ref="form">
				<b-form-group :state="nameState" label="Name" label-for="userName-input" invalid-feedback="Name is required" >
					<b-form-input id="userName-input" v-model="userAddForm.userName" :state="nameState" required>
					</b-form-input>
				</b-form-group>
				<b-form-group :state="emailState" label="Email Address" label-for="email-input" invalid-feedback="Email address is required" >
					<b-form-input id="email-input" v-model="userAddForm.email" :state="emailState" required>
					</b-form-input>
				</b-form-group>
				<b-form-group :state="roleState" label="Role" label-for="role-input" invalid-feedback="role is required" >
					<select class="form-control" :required="true" @change="onChangeSelectAdd">
						<option v-for="role in roleValue" :value="role.id" :selected="1 === role.id">
							{{ role.name }}
						</option>
					</select>
				</b-form-group>
			</form>
			<hr>
			<b-row>
				<b-col>
					<b-button block @click="hideModal">Cancel</b-button>
				</b-col>
				<b-col>
					<b-button block variant="primary" @click="addUser">Add User</b-button>
				</b-col>
			</b-row>
		</b-modal>
		<b-modal ref="deleteUserModal" hide-footer title="Delete User" id="deleteUserModal">
			<label>Are you sure you want to delete this user?</label>
			<hr>
			<b-row>
				<b-col>
					<b-row>
						<b-col>
							<b-button block @click="$bvModal.hide('deleteUserModal')">Cancel</b-button>
						</b-col>
						<b-col>
							<b-button block variant="danger" @click="deleteUser">Delete</b-button>
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
			userForm :{
				userName: '',
				email: '',
				role_id: '',
				key: ''
			},
			userAddForm:{
				userName: '',
				email: '',
				role_id: 1
			},
			passwordForm :{
				oldPass: '',
				newPass: '',
				confirmedPass: ''
			},
			roleValue : '',
			users : null,			
			nameState: null,			
			emailState: null,			
			roleState: null,
			oldPassState: null,			
			newPassState: null,			
			confirmedPassState: null,

			styles: '',
			userAdminStyles: '',
			loggedInUser: ''
		}
	},
	created(){
		this.setupUser()
		this.setupRoleCategory()
	},
	methods:{
		setupUser(){
			axios.post('getuserdetails')
			.then(response=>{
				this.loggedInUser = response.data.loggedInUser
				if(response.data.role == 1){
					this.styles = { 'display': 'none' }
					this.userAdminStyles = ''
				}
				else{
					this.styles = ''
					this.userAdminStyles = { 'display': 'none' }
					alert("You don't have any access here!")
					window.location.href = 'dashboard'
				}

				if(response.data.status_code == 0){
					alert("There was an error. Please check the following:\n\n*   " + response.data.msg)
				}
				else if(response.data.status_code == 1){
					this.users = response.data.msg
				}
				else{
					alert(response.data.msg)
				}

			})
		},
		setupRoleCategory(){
			axios.post('displayuserrole')
			.then(response=>{
				if(response.data.status_code == 0){
					alert("There was an error. Please check the following:\n\n*   " + response.data.msg)
				}
				else if(response.data.status_code == 1){
					this.roleValue =  response.data.msg
				}
				else{
					alert(response.data.msg)
				}

			})
			
		},
		viewUser(users){
			console.log(users)
			this.$refs['updateUserModal'].show()
			this.userForm.userName = users.name
			this.userForm.email = users.email
			this.userForm.key = users.id
			this.userForm.role_id = users.role_id
		},
		deleteModal(){
			this.$refs['deleteUserModal'].show()
		},
		showAddUser(){
			this.$refs['addUserModal'].show()
		},
		hideModal() {
			this.$refs['updateUserModal'].hide()
			this.$refs['deleteUserModal'].hide()
			this.$refs['addUserModal'].hide()
			this.$refs['changePasswordModal'].hide()
			this.$refs['confirmChangeModal'].hide()

			let vm = this
			vm.userForm.userName = ''
			vm.userForm.email = ''
			vm.userForm.role_id = ''
			vm.userForm.key = ''

			vm.userAddForm.userName = ''
			vm.userAddForm.email = ''
			vm.userAddForm.role_id = 1
			vm.userAddForm.role = ''
			
			vm.passwordForm.oldPass = ''
			vm.passwordForm.newPass = ''
			vm.passwordForm.confirmedPass = ''

			vm.nameState = null
			vm.emailState = null
			vm.roleState = null
			vm.oldPassState = null
			vm.newPassState = null
			vm.confirmedPassState = null

			vm.selectedRole = 'Administrator'
		},
		updateUser(e) {		
			let vm = this
			console.log(vm.userForm)
			axios.post('validateupdateuser', vm.userForm)
            .then(function (response) {
            	if(response.data.status_code == 0){
            		alert(response.data.msg)
            	}
            	else if(response.data.status_code == 1){
            		alert('Update Successfully')
            		window.location.href = 'user-management-user'
            	}
            	else{
            		alert(response.data.msg)
            	}
            });	
	        this.$refs['updateUserModal'].hide()
	    },
	    deleteUser(e){
	    	let vm = this
	    	axios.post('deleteuser', vm.userForm)
            .then(function (response) {
            	if(response.data.status_code == 0){
            		alert(response.data.msg)
            	}
            	else if(response.data.status_code == 1){
            		alert('User is deleted successfully')
        			window.location.href = 'user-management-user'
            	}
            	else{
            		alert(response.data.msg)
            	}
            });	
	    },
	    addUser(e){
	    	let vm = this
	    	axios.post('adduser', vm.userAddForm)
            .then(function (response) {
            	if(response.data.status_code == 0){
            		alert(response.data.msg)
            	}
            	else if(response.data.status_code == 1){
            		alert('New user is added successfully.')
            		window.location.href = 'user-management-user'
            	}
            	else{
            		alert(response.data.msg)
            	}
            });
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
		},
		onChangeSelect(event) {
      		this.userForm.role_id = event.target.value
		},
		onChangeSelectAdd(event) {
      		this.userAddForm.role_id = event.target.value
		}
	}
}
</script>