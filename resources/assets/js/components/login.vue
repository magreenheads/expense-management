<template>
	<div class="row">
		<div class="col-md-4 offset-md-4">
			<div class="card" style="">
				<div class="card-body">
					<h5 class="card-title" style="text-align: center;">Expense Manager</h5>
					<h6 class="card-subtitle mb-2 text-muted" style="text-align: center;">Login</h6>
					<hr>
					<form>
		                <div class="form-group" :class="{'has-error' : errorsEmail}">
		                    <label for="exampleInputEmail1">Email address</label>
		                    <div class="input-group">
		                        <div class="input-group-prepend">
		                            <div class="input-group-text"><i class="far fa-envelope"></i></div>
		                        </div>
		                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" v-model="loginDetails.email" v-on:keyup.enter="loginPost">                           
		                    </div>
		                </div>
		                <div class="form-group" :class="{'has-error' : errorsPassword}">
		                    <label for="exampleInputEmail1">Password</label>
		                    <div class="input-group">
		                        <div class="input-group-prepend">
		                            <div class="input-group-text"><i class="fas fa-lock"></i>
		                        </div>
		                    </div>
		                    <input type="password" class="form-control" id="exampleInputPassword" aria-describedby="emailHelp" placeholder="Password" v-model="loginDetails.password" v-on:keyup.enter="loginPost">
		                    </div>
		                </div>
		                <button type="button" @click.prevent="loginPost" class="btn btn-primary w-100" id="btnLogin">Login</button>
		            </form>					
				</div>
			</div>
		</div>
	</div>
</template>

<script>
 	
    export default {
        data(){
            return{
        		loginDetails : {
        			email:'',
            		password:''
        		},                
	            errorsEmail: false,
	            errorsPassword: false,
	            emailError:null,
	            passwordError:null
            }
        },
        methods:{
        	onEnter(e){
        		if (e.keyCode === 13) {
        			loginPost
        		}
        	},
	        loginPost(e){
        		let vm = this;
	            axios.post('validatelogin', vm.loginDetails)
	            .then(function (response) {
	            	console.log(response)
	            	if(response.data.status_code == 0){
                		alert("There was an error. Please check the following:\n\n*   " + response.data.msg)
                	}
                	else if(response.data.status_code == 1){
                		window.location.href = 'dashboard'
                	}
                	else{
                		alert(response.data.msg)
                	}
	            })
	            .catch(function (error) {
	                var errors = error.response
	                if(errors.statusText === 'Unprocessable Entity'){
	                    if(errors.data){
	                        if(errors.data.email){
	                           vm.errorsEmail = true
	                           vm.emailError = _.isArray(errors.data.email) ? errors.data.email[0]: errors.data.email
	                        }
	                        if(errors.data.password){
	                           vm.errorsPassword = true
	                           vm.passwordError = _.isArray(errors.data.password) ? errors.data.password[0] : errors.data.password
	                        }
	                    }
	                }
	            });        	
	        }
        },
        mounted() {
        }
    }
</script>