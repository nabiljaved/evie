const n_form = document.getElementById("form");
    n_form.addEventListener("submit", function(e) {
    var email = document.querySelector("#n_email").value;
    e.preventDefault();
    signupHandler(email);		
});
    const signupHandler = async (email) => {
    console.log(email)
    var container = document.getElementById("newsletter-container");
    var element = document.querySelector("#n_email");		
    const response = await axios.post('http://localhost/wordpress/post.php', {
    email,
 });
  if(response.data){
    element.value = "";
    container.style.display = "block";
    setTimeout(()=>{
        container.style.display = "none";
    },2000)
 }else{
   element.value = "";
   container.style.display = "none";
   alert("please enter correct email");
 }
};

