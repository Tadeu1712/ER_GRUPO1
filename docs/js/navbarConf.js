function pedidosDropDown() {
    document.getElementById("dropDownPedidos").classList.toggle("show");
    }

function loginDropDown()
{
    document.getElementById("dropDownAccount").classList.toggle("show");
}

function reclamacaoDropDown() {
    document.getElementById("dropDownReclamacao").classList.toggle("show");
}

function selectFunc() {
    
}  

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
        }
        }
    }
    }