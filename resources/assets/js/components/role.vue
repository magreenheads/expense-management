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
						<a href="user-management-role" class="list-group-item list-group-item-action active" style="padding-left: 45px;">
							Roles
						</a>
						<a href="user-management-user" class="list-group-item list-group-item-action " style="padding-left: 45px;">
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
						<div class="col-md-10 offset-md-1" v-bind:style="roleAdminStyles">
							<div class="row">
								<div class="col-md-6">
									<h4>Roles</h4>
								</div>
								<div class="col-md-6" style="text-align:right">
									<h5>User Management > Roles</h5>
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
											<tr v-for="roles in role" @click="viewRole(roles)">						
												<td>{{roles.display_name}}</td>
												<td>{{roles.description}}</td>
												<td>{{roles.created_at}}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2 offset-md-10">
									  <b-button variant="info" class="float-right btn-block" @click="showAddRole">Add Role</b-button>
								</div>
							</div>
						</div>
					</div>
				</div>			
			</div>
		</div>
		<b-modal ref="updateRoleModal" hide-footer title="Update Role">
			<form ref="form">
				<b-form-group :state="nameState" label="Role Name" label-for="roleName-input" invalid-feedback="Role name is required" >
					<b-form-input id="roleName-input" v-model="roleForm.roleName" :state="nameState" required>
					</b-form-input>
				</b-form-group>
				<b-form-group :state="descState" label="Description" label-for="description-input" invalid-feedback="Description is required" >
					<b-form-input id="description-input" v-model="roleForm.description" :state="descState" required>
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
							<b-button block variant="primary" @click="updateRole">Update</b-button>
						</b-col>
					</b-row>
				</b-col>
			</b-row>
		</b-modal>
		<b-modal ref="addRoleModal" hide-footer title="Add Role">
			<form ref="form">
				<b-form-group :state="nameState" label="Role Name" label-for="roleName-input" invalid-feedback="Role name is required" >
					<b-form-input id="roleName-input" v-model="addRoleForm.roleName" :state="nameState" required>
					</b-form-input>
				</b-form-group>
				<b-form-group :state="descState" label="Description" label-for="description-input" invalid-feedback="Description is required" >
					<b-form-input id="description-input" v-model="addRoleForm.description" :state="descState" required>
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
							<b-button block variant="primary" @click="addRole">Save</b-button>
						</b-col>
					</b-row>
				</b-col>
			</b-row>
		</b-modal>
		<b-modal ref="deleteRoleModal" hide-footer title="Delete Role" id="deleteRoleModal">
			<label>Are you sure you want to delete this role?. This may remove registered user under this role. Click delete to continue</label>
			<hr>
			<b-row>
				<b-col>
					<b-row>
						<b-col>
							<b-button block @click="$bvModal.hide('deleteRoleModal')">Cancel</b-button>
						</b-col>
						<b-col>
							<b-button block variant="danger" @click="deleteRole">Delete</b-button>
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
			roleForm :{
				roleName: '',
				description: '',
				key : ''
			},
			addRoleForm:{
				roleName: '',
				description: ''
			},
			passwordForm :{
				oldPass: '',
				newPass: '',
				confirmedPass: ''
			},
			role : null,			
			nameState: null,			
			descState: null,
			oldPassState: null,			
			newPassState: null,			
			confirmedPassState: null,

			styles: '',
			roleAdminStyles: '',
			loggedInUser: ''
		}
	},
	created(){
		this.setupRole()
	},
	methods:{
		setupRole(){
			axios.post('getuserrole')
			.then(response=>{
				this.loggedInUser = response.data.loggedInUser
				if(response.data.role == 1){
					this.styles = { 'display': 'none' }
					this.roleAdminStyles = ''
				}
				else{
					this.styles = ''
					this.roleAdminStyles = { 'display': 'none' }
					alert("You don't have any access here!")
					window.location.href = 'dashboard'
				}

				if(response.data.status_code == 0){
					alert("There was an error. Please check the following:\n\n*   " + response.data.msg)
				}
				else if(response.data.status_code == 1){
					this.role = response.data.msg
				}
				else{
					alert(response.data.msg)
				}

			})
		},
		viewRole(roles){
			this.$refs['updateRoleModal'].show()
			this.roleForm.roleName = roles.display_name
			this.roleForm.description = roles.description
			this.roleForm.key = roles.id
		},
		deleteModal(){
			this.$refs['deleteRoleModal'].show()
		},
		showAddRole(){
			this.$refs['addRoleModal'].show()
		},
		hideModal() {
			this.$refs['updateRoleModal'].hide()
			this.$refs['deleteRoleModal'].hide()
			this.$refs['addRoleModal'].hide()
			this.$refs['changePasswordModal'].hide()
			this.$refs['confirmChangeModal'].hide()

			let vm = this
			vm.roleForm.roleName = ''
			vm.roleForm.description = ''
			vm.roleForm.key = ''

			vm.addRoleForm.roleName = ''
			vm.addRoleForm.description = ''
			
			vm.passwordForm.oldPass = ''
			vm.passwordForm.newPass = ''
			vm.passwordForm.confirmedPass = ''

			vm.nameState = null
			vm.descState = null
			vm.oldPassState = null
			vm.newPassState = null
			vm.confirmedPassState = null
		},
		updateRole(e) {		
			let vm = this
			axios.post('validateupdaterole', vm.roleForm)
            .then(function (response) {
            	if(response.data.status_code == 0){
            		alert(response.data.msg)
            	}
            	else if(response.data.status_code == 1){
            		alert('Update Successfully')
            		window.location.href = 'user-management-role'
            	}
            	else{
            		alert(response.data.msg)
            	}
            });	
	        this.$refs['updateRoleModal'].hide()
	    },
	    deleteRole(e){
	    	let vm = this
	    	axios.post('deleterole', vm.roleForm)
            .then(function (response) {
            	if(response.data.status_code == 0){
            		alert(response.data.msg)
            	}
            	else if(response.data.status_code == 1){
            		alert('Role is deleted successfully')
            		window.location.href = 'user-management-role'
            	}
            	else{
            		alert(response.data.msg)
            	}
            });	
	    },
	    addRole(e){
	    	let vm = this
	    	axios.post('addrole', vm.addRoleForm)
            .then(function (response) {
            	console.log(response)
            	if(response.data.status_code == 0){
            		alert(response.data.msg)
            	}
            	else if(response.data.status_code == 1){
            		alert('New role is added successfully.')
            		window.location.href = 'user-management-role'
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
		}
	}
}
</script>