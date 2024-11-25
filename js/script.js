let body = document.body;

let profile = document.querySelector('.header .flex .profile');

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active');
   searchForm.classList.remove('active');
}

let searchForm = document.querySelector('.header .flex .search-form');

document.querySelector('#search-btn').onclick = () =>{
   searchForm.classList.toggle('active');
   profile.classList.remove('active');
}

let sideBar = document.querySelector('.side-bar');

document.querySelector('#menu-btn').onclick = () =>{
   sideBar.classList.toggle('active');
   body.classList.toggle('active');
}

document.querySelector('.side-bar .close-side-bar').onclick = () =>{
   sideBar.classList.remove('active');
   body.classList.remove('active');
}

document.querySelectorAll('input[type="number"]').forEach(InputNumber => {
   InputNumber.oninput = () =>{
      if(InputNumber.value.length > InputNumber.maxLength) InputNumber.value = InputNumber.value.slice(0, InputNumber.maxLength);
   }
});

window.onscroll = () =>{
   profile.classList.remove('active');
   searchForm.classList.remove('active');

   if(window.innerWidth < 1200){
      sideBar.classList.remove('active');
      body.classList.remove('active');
   }

}

let toggleBtn = document.querySelector('#toggle-btn');
let darkMode = localStorage.getItem('dark-mode');

const enabelDarkMode = () =>{
   toggleBtn.classList.replace('fa-sun', 'fa-moon');
   body.classList.add('dark');
   localStorage.setItem('dark-mode', 'enabled');
}

const disableDarkMode = () =>{
   toggleBtn.classList.replace('fa-moon', 'fa-sun');
   body.classList.remove('dark');
   localStorage.setItem('dark-mode', 'disabled');
}

if(darkMode === 'enabled'){
   enabelDarkMode();
}

toggleBtn.onclick = (e) =>{
   let darkMode = localStorage.getItem('dark-mode');
   if(darkMode === 'disabled'){
      enabelDarkMode();
   }else{
      disableDarkMode();
   }
}

// Validate passwords
const passwordInput = document.getElementById('regPass');
const confirmPasswordInput = document.getElementById('regConfPass');
const passwordError = document.getElementById('passwordError');

function validatePasswords() {
  const password = passwordInput.value;
  const confirmPassword = confirmPasswordInput.value;

  if (password && confirmPassword && password !== confirmPassword) {
    passwordError.textContent = 'Passwords do not match!';
  } else {
    passwordError.textContent = '';
  }
}


//Function for Validation to South African ID number
passwordInput.addEventListener('input', validatePasswords);
confirmPasswordInput.addEventListener('input', validatePasswords);

      
        const lengthCircle = document.getElementById('length-circle');
        const uppercaseCircle = document.getElementById('uppercase-circle');
        const lowercaseCircle = document.getElementById('lowercase-circle');
        const numberCircle = document.getElementById('number-circle');
        const specialCharCircle = document.getElementById('special-char-circle');

        passwordInput.addEventListener('input', updateCircles);

        function updateCircles() {
            const password = passwordInput.value;
            lengthCircle.classList.toggle('valid', password.length >= 8);
            uppercaseCircle.classList.toggle('valid', /[A-Z]/.test(password));
            lowercaseCircle.classList.toggle('valid', /[a-z]/.test(password));
            numberCircle.classList.toggle('valid', /\d/.test(password));
            specialCharCircle.classList.toggle('valid', /[!@#$%^&*]/.test(password));
        }


        function validateID() {
         const idInput = document.getElementById("id-number");
         const birthdateInput = document.getElementById("birthdate");
         const errorElement = document.getElementById("idError");
       
         // Remove non-numeric characters
         idInput.value = idInput.value.replace(/\D/g, "");
       
         const id = idInput.value;
       
         // Validate length and numeric format
         if (id.length !== 13) {
           errorElement.textContent = "Invalid ID";
           errorElement.style.display = "block";
           birthdateInput.value = "";
           return false;
         }
       
         // Extract components
         const birthdate = id.substring(0, 6); // YYMMDD
         const year = parseInt(birthdate.substring(0, 2), 10) + (birthdate[0] <= '2' ? 2000 : 1900);
         const month = parseInt(birthdate.substring(2, 4), 10) - 1; // Months are zero-indexed
         const day = parseInt(birthdate.substring(4, 6), 10);
       
         // Validate birthdate
         const birthdateObject = new Date(year, month, day);
         if (
           birthdateObject.getFullYear() !== year ||
           birthdateObject.getMonth() !== month ||
           birthdateObject.getDate() !== day
         ) {
           errorElement.textContent = "Invalid ID";
           errorElement.style.display = "block";
           birthdateInput.value = "";
           return false;
         }
       
         // Validate Luhn checksum
         const luhnCheck = (num) => {
           let sum = 0;
           let alternate = false;
           for (let i = num.length - 1; i >= 0; i--) {
             let n = parseInt(num[i], 10);
             if (alternate) {
               n *= 2;
               if (n > 9) n -= 9;
             }
             sum += n;
             alternate = !alternate;
           }
           return sum % 10 === 0;
         };
       
         if (!luhnCheck(id)) {
           errorElement.textContent = "Invalid ID";
           errorElement.style.display = "block";
           birthdateInput.value = "";
           return false;
         }
       
         // If valid, hide error and auto-fill birthdate
         errorElement.style.display = "none";
         const formattedDate = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`; // Format as yyyy-mm-dd
         birthdateInput.value = formattedDate;
       
         return true;
       }
       


       