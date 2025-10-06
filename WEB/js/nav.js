/* Hamburger */

var nav_hamburger = document.querySelector("#nav_hamburger");

nav_hamburger.addEventListener("click", function(){
    nav_hamburger.classList.toggle("active");
    if (document.querySelector(".nav-modal").classList.contains("active") == true) { 
        document.querySelector(".nav-modal").classList.add("close");
        setTimeout(()=>{
            document.querySelector(".nav-modal").classList.remove("close");
            document.querySelector(".nav-modal").classList.remove("active");
        }, (300));
    } else {
        document.querySelector(".nav-modal").classList.add("active");
    }
});