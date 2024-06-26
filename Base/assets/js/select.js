
function selectAddPokemon(){
    const SELECTADDBUTTON = document.querySelector('#add-button');
    
    SELECTADDBUTTON.addEventListener('click', (e)=>{
        if(SELECTADDBUTTON){
            console.log('tu as cliqué sur moi');
        } else{
            console.log('error 404 no found');
        }
        
    });
}
selectAddPokemon();