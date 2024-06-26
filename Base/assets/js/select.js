
function printAddPokemonForm() {
    const selectAddButton = document.querySelector('#add-button');

   selectAddButton.addEventListener('click', (e)=>{
    console.log('tu as cliqué sur moi bien joué');
    let modalFont = document.querySelector('#myModal');
    console.log(modalFont);

    let modalPrint = document.querySelector('.modal-content');
    console.log(modalPrint);

    if(modalFont && modalPrint){
        modalFont.style.display="block";
        modalPrint.style.display="block";
    }
   })
}

function closeAddPokemonForm(){
    const CLOSEFORM = document.querySelector('.close');
    CLOSEFORM.addEventListener('click', (c)=>{
        console.log('tu as cliqué sur moi bien joué');
    let modalFont = document.querySelector('#myModal');
    console.log(modalFont);

    let modalPrint = document.querySelector('.modal-content');
    console.log(modalPrint);

    if(modalFont && modalPrint){
        modalFont.style.display="none";
        modalPrint.style.display="none";
    }
    })
}

export {printAddPokemonForm}; 
export {closeAddPokemonForm};
