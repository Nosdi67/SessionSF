fetch('http://127.0.0.1:8000/api/membres?page=1')
.then((response) => response.json())
.then((data) => {
    const membres = data["hydra:member"];
    membres.forEach(membre=>{
        console.log(membre)
    });
})

// fetch('http://127.0.0.1:8000/api/membres', {
//     mode:  'no-cors',
//     method: 'GET',
//     headers: {
//       'Content-Type': 'application/json'
//     },
//   })
//   .then(response => response.json())
//   .then(data => console.log(data))
//   .catch(error => console.error(error));

