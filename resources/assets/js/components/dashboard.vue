
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
						<a href="dashboard" class="list-group-item list-group-item-action active">
							Dashboard
						</a>
						<a href="" class="list-group-item list-group-item-action disabled">
							User Management
						</a>
						<a href="user-management-role" class="list-group-item list-group-item-action " style="padding-left: 45px;">
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
								<a class="nav-link" style="padding-right: 25px; cursor: pointer;" v-bind:style="styles" @click='showChangePassword' >Change Password</a>
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
						<div class="col-md-10 offset-md-1">
							<div class="row">
								<div class="col-md-6">
									<h4>My Expenses</h4>
								</div>
								<div class="col-md-6" style="text-align:right">
									<h5>DashBoard</h5>
								</div>
							</div>
							
							<br>
							<br>
							<div class="row">
								<div class="col-md-5">
									<table class="table">
										<thead>
											<tr>
												<th scope="col">Expense Categories</th>
												<th scope="col">Total</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="category in expenseCat">								
												<td>{{category.category_name}}</td>
												<td>{{category.total}}</td>
											</tr>
										</tbody>
									</table>
								</div>	
								<div class="col-md-7"></div>
							</div>
						</div>
					</div>
				</div>		
			</div>
		</div>
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
	import { Pie } from 'vue-chartjs'
	export default {
		data(){
			return{
				passwordForm :{
					oldPass: '',
					newPass: '',
					confirmedPass: ''
				},
				expenseCat : null,			
				oldPassState: null,			
				newPassState: null,			
				confirmedPassState: null,

				styles: '',
				loggedInUser: ''

			}
		},
		created(){
			this.setupDashboard()
		},
		methods:{
			setupDashboard(){
				axios.post('getexpensecategory')
				.then(response=>{
					this.loggedInUser = response.data.loggedInUser
					if(response.data.role == 1){
						this.styles = { 'display': 'none' }
					}
					else{
						this.styles = ''
					}

					if(response.data.status_code == 0){
                		alert("There was an error. Please check the following:\n\n*   " + response.data.msg)
                	}
                	else if(response.data.status_code == 1){
                		this.expenseCat = response.data.msg
                	}
                	else{
                		alert(response.data.msg)
                	}
			        
			    })
			},
			showChangePassword(){
				this.$refs['changePasswordModal'].show()
			},
			hideModal(){
				this.$refs['changePasswordModal'].hide()
				this.$refs['confirmChangeModal'].hide()

				let vm = this
				vm.passwordForm.oldPass = ''
				vm.passwordForm.newPass = ''
				vm.passwordForm.confirmedPass = ''
			
				vm.oldPassState = null
				vm.newPassState = null
				vm.confirmedPassState = null
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
				


