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
						<a href="expense-category" class="list-group-item list-group-item-action " style="padding-left: 45px;">
							Expense Categories
						</a>
						<a href="expenses" class="list-group-item list-group-item-action active" style="padding-left: 45px;">
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
						<div class="col-md-10 offset-md-1">
							<div class="row">
								<div class="col-md-6">
									<h4>Expenses</h4>
								</div>
								<div class="col-md-6" style="text-align:right">
									<h5>Expense Management > Expenses</h5>
								</div>
							</div>
							
							<br>
							<br>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-hover">
										<thead>
											<tr>
												<th scope="col">Expense Category</th>
												<th scope="col">Amount</th>
												<th scope="col">Entry Date</th>
												<th scope="col">Created at</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="expense in expenses" @click="viewExpenses(expense)">
												<td>{{expense.display_name}}</td>
												<td>{{expense.amount}}</td>
												<td>{{expense.entry_date}}</td>
												<td>{{expense.created_at}}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2 offset-md-10">
									  <b-button variant="info" class="float-right btn-block" @click="addCategory">Add Expenses</b-button>
								</div>
							</div>
						</div>
					</div>
				</div>			
			</div>
		</div>

		<b-modal ref="updateExpensesModal" hide-footer title="Update Category">
			<form ref="form">
				<b-form-group :state="nameState" label="Category Name" label-for="categoryName-input" invalid-feedback="Category name is required" >
					<select class="form-control" :required="true" @change="onChangeSelect">
						<option v-for="category in expensecategory" :value="category.id" :selected="ExpenseForm.expensecat === category.id">
							{{ category.name }}
						</option>
						
					</select>
				</b-form-group>
				<b-form-group :state="descState" label="Amount" label-for="description-input" invalid-feedback="Description is required" >
					<b-form-input id="amount-input" v-model="ExpenseForm.amount" :state="descState" required>
					</b-form-input>
				</b-form-group>
				<b-form-group :state="descState" label="Description" label-for="description-input" invalid-feedback="Description is required" >
					<b-form-input id="date-input" v-model="ExpenseForm.entry_date" :state="descState"  type="date" required>
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
		<b-modal ref="deleteExpenseModal" hide-footer title="Delete Category" id="deleteCategoryModal">
			<label>Are you sure you want to delete this expenses? Click delete to continue</label>
			<hr>
			<b-row>
				<b-col>
					<b-row>
						<b-col>
							<b-button block @click="hideModal">Cancel</b-button>
						</b-col>
						<b-col>
							<b-button block variant="danger" @click="deleteExpenses">Delete</b-button>
						</b-col>
					</b-row>
				</b-col>
			</b-row>
		</b-modal>
		<b-modal ref="addExpensesModal" hide-footer title="Add Category">
			<form ref="form">
				<b-form-group :state="nameState" label="Category Name" label-for="categoryName-input" invalid-feedback="Category name is required" >
					<select class="form-control" :required="true" @change="onChangeSelectAdd">
						<option v-for="category in expensecategory" :value="category.id" :selected="'1' === category.id">
							{{ category.name }}
						</option>
					</select>
				</b-form-group>
				<b-form-group :state="descState" label="Amount" label-for="description-input" invalid-feedback="Description is required" >
					<b-form-input id="amount-input" v-model="addExpensesForm.amount" :state="descState" required>
					</b-form-input>
				</b-form-group>
				<b-form-group :state="descState" label="Description" label-for="description-input" invalid-feedback="Description is required" >
					<b-form-input id="date-input" v-model="addExpensesForm.entry_date" :state="descState"  type="date" required>
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
							<b-button block variant="primary" @click="addCategoryModal">Add</b-button>
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
			ExpenseForm :{
				expensecat: '',
				amount: '',
				entry_date: '',
				key : ''
			},
			addExpensesForm:{
				expensecat: 1,
				amount: '',
				entry_date: ''
			},
			passwordForm :{
				oldPass: '',
				newPass: '',
				confirmedPass: ''
			},
			expensecategory : null,
			expenses : null,			
			nameState: null,			
			descState: null,
			oldPassState: null,			
			newPassState: null,			
			confirmedPassState: null,

			styles: '',
			loggedInUser: ''
		}
	},
	created(){
		this.setupExpenses()
	},
	methods:{
		setupExpenses(){
			axios.post('getexpensedetails')
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
					this.expenses = response.data.msg
					this.expensecategory =  response.data.cat
				}
				else{
					alert(response.data.msg)
				}

			})
		},
		viewExpenses(categories){
			this.$refs['updateExpensesModal'].show()
			this.ExpenseForm.amount = categories.amount
			this.ExpenseForm.entry_date = categories.entry_date
			this.ExpenseForm.key = categories.id

      		this.ExpenseForm.expensecat = categories.id
		},
		updateCategory() {
			let vm = this
			axios.post('validateupdateexpenses', vm.ExpenseForm)
            .then(function (response) {
            	if(response.data.status_code == 0){
            		alert(response.data.msg)
            	}
            	else if(response.data.status_code == 1){
            		alert('Update Successfully')
            		window.location.href = 'expenses'
            	}
            	else{
            		alert(response.data.msg)
            	}
            });	
	        this.$refs['updateExpensesModal'].hide()
		},
		deleteModal(){
			this.$refs['deleteExpenseModal'].show()
		},
	    deleteExpenses(e){
	    	let vm = this
			axios.post('validatedeleteexpenses', vm.ExpenseForm)
            .then(function (response) {
            	if(response.data.status_code == 0){
            		alert(response.data.msg)
            	}
            	else if(response.data.status_code == 1){
            		alert('Delete Successfully')
            		window.location.href = 'expenses'
            	}
            	else{
            		alert(response.data.msg)
            	}
            });	
	        this.$refs['deleteExpenseModal'].hide()
	    },
		hideModal() {
			this.$refs['updateExpensesModal'].hide()
			this.$refs['deleteExpenseModal'].hide()
			this.$refs['addExpensesModal'].hide()
			this.$refs['changePasswordModal'].hide()
			this.$refs['confirmChangeModal'].hide()

			let vm = this
			vm.ExpenseForm.expensecat = ''
			vm.ExpenseForm.amount = ''
			vm.ExpenseForm.entry_date = ''
			vm.ExpenseForm.key = ''

			vm.addExpensesForm.expensecat = 1
			vm.addExpensesForm.amount = ''
			vm.addExpensesForm.entry_date = ''

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
			this.addExpensesForm.entry_date = new Date().toISOString().slice(0,10)
			this.$refs['addExpensesModal'].show()
		},
		addCategoryModal() {
			let vm = this
			axios.post('validateaddexpenses', vm.addExpensesForm)
            .then(function (response) {
            	if(response.data.status_code == 0){
            		alert(response.data.msg)
            	}
            	else if(response.data.status_code == 1){
            		alert('New expenses is added successfully')
            		window.location.href = 'expenses'
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
      		this.ExpenseForm.expensecat = event.target.value
		},
		onChangeSelectAdd(event) {
      		this.addExpensesForm.expensecat = event.target.value
		}
	}
}
</script>