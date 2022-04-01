const flash = {
    
    launchFlashMessage : setTimeout(() => {
        
        let flashes = document.querySelector('.alert-success');
                 
        if (flashes) {
            
            flashes.style.transition = "opacity " + 3 + "s";
            flashes.style.opacity = 0;
            flashes.addEventListener("transitionend", function () {
                flashes.style.display = "none";
            })
        }   

    }, 3000),
}