document.addEventListener("DOMContentLoaded",()=>{
  const logout=document.getElementById("logoutBtn");
  const welcome=document.getElementById("welcomeUser");

  // Protect private pages
  if(window.location.pathname.includes(".html") &&
     !window.location.pathname.includes("index.html") &&
     !window.location.pathname.includes("signup.html")) {
    if(localStorage.getItem("loggedIn")!=="true"){
      alert("Please login first!");
      window.location.href="index.html";
    } else {
      const u=localStorage.getItem("loggedInUser")||"User";
      if(welcome) welcome.textContent="Welcome, "+u;
    }
  }

  // Signup
  const signupForm=document.getElementById("signupForm");
  if(signupForm){
    signupForm.addEventListener("submit",e=>{
      e.preventDefault();
      localStorage.setItem("userEmail",document.getElementById("signupEmail").value);
      localStorage.setItem("userPass",document.getElementById("signupPassword").value);
      localStorage.setItem("loggedInUser",document.getElementById("signupUsername").value);
      alert("Signup successful! Please login.");
      window.location.href="index.html";
    });
  }

  // Login
  const loginForm=document.getElementById("loginForm");
  if(loginForm){
    loginForm.addEventListener("submit",e=>{
      e.preventDefault();
      if(document.getElementById("loginEmail").value===localStorage.getItem("userEmail")
         && document.getElementById("loginPassword").value===localStorage.getItem("userPass")){
        localStorage.setItem("loggedIn","true");
        window.location.href="home.html";
      } else { alert("Invalid credentials!"); }
    });
  }

  // Logout
  if(logout){
    logout.addEventListener("click",()=>{
      localStorage.clear();
      window.location.href="index.html";
    });
  }
});
