<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password</title>
<!--hello world-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body{background:#f4f6fb}
.step{display:none}
.step.active{display:block}
.progress-steps span{width:30px;height:30px;border-radius:50%;display:inline-flex;
align-items:center;justify-content:center;background:#ddd;margin:0 5px}
.progress-steps .done{background:#28a745;color:#fff}
</style>
</head>

<body>

<section class="py-5">
<div class="container">
<div class="row justify-content-center">
<div class="col-md-6 col-lg-5">

<div class="card shadow border-0">
<div class="card-body p-4">

<h3 class="text-center mb-3">Forgot Password</h3>

<div class="text-center progress-steps mb-4">
<span id="s1" class="done">1</span>
<span id="s2">2</span>
<span id="s3">3</span>
</div>

<div id="msg" class="alert alert-danger d-none"></div>

<!-- STEP 1 EMAIL -->
<div id="step1" class="step active">
<label>Email Address</label>
<input id="email" class="form-control mb-3" placeholder="Enter email">
<button class="btn btn-primary w-100" onclick="sendOTP()">Send OTP</button>
</div>

<!-- STEP 2 OTP -->
<div id="step2" class="step">
<p class="text-center small">OTP sent to <b id="showEmail"></b></p>
<input id="otp" class="form-control text-center mb-3" maxlength="6" placeholder="Enter 6-digit OTP">
<button class="btn btn-success w-100" onclick="verifyOTP()">Verify OTP</button>
</div>

<!-- STEP 3 RESET -->
<div id="step3" class="step">
<input id="pass" type="password" class="form-control mb-2" placeholder="New Password">
<input id="cpass" type="password" class="form-control mb-3" placeholder="Confirm Password">
<button class="btn btn-success w-100" onclick="resetPassword()">Reset Password</button>
</div>

</div>
</div>

</div>
</div>
</div>
</section>

<script>
let generatedOTP="";

// switch steps
function goStep(n){
 document.querySelectorAll(".step").forEach(s=>s.classList.remove("active"));
 document.getElementById("step"+n).classList.add("active");
 document.getElementById("s"+n).classList.add("done");
}

// STEP 1 SEND OTP
function sendOTP(){
 let email=document.getElementById("email").value.trim();
 if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){
  showMsg("Enter valid email"); return;
 }

 generatedOTP=Math.floor(100000+Math.random()*900000);
 localStorage.setItem("demoOTP",generatedOTP);
 localStorage.setItem("demoEmail",email);

 alert("Demo OTP: "+generatedOTP); // remove later
 document.getElementById("showEmail").innerText=email;
 goStep(2);
}

// restrict OTP input to digits only
document.getElementById("otp").addEventListener("input",(e)=>{
 e.target.value=e.target.value.replace(/\D/g,'').slice(0,6);
});

// STEP 2 VERIFY OTP
function verifyOTP(){
 let otp=document.getElementById("otp").value;
 if(otp===localStorage.getItem("demoOTP")) goStep(3);
 else showMsg("Invalid OTP");
}

// STEP 3 RESET PASSWORD
function resetPassword(){
 let p=document.getElementById("pass").value;
 let c=document.getElementById("cpass").value;

 if(p.length<6) return showMsg("Password must be 6 characters");
 if(p!==c) return showMsg("Passwords do not match");

 alert("Password Reset Successful ");
 localStorage.clear();
 location.reload();
}

function showMsg(msg){
 let m=document.getElementById("msg");
 m.innerText=msg; m.classList.remove("d-none");
}
</script>

</body>
</html>
