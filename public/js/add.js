// let pForm = $('pform');
pForm.addEventListener('submit', async (e)  => {
    e.preventDefault()
    //Je récupère les valeurs du formulaire
    const data = {
        name: document.getElementById('add_produit_name').value,
        description: document.getElementById('add_produit_description').value
    }
    const response = await fetch('/produit/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)

    });
    console.log(await response.json());

})