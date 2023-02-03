const n_form = document.getElementById("form");
    n_form.addEventListener("submit", function(e) {
    var email = document.querySelector("#n_email").value;	
    e.preventDefault();
    signupHandler(email);		
});
    const signupHandler = async (email) => {
    console.log(email)	
    const response = await axios.post('http://localhost/wordpress/post.php', {
    email,
 });
  if(response.data){
  alert(response.data);
 }else{
   alert("please enter correct email");
 }
};

