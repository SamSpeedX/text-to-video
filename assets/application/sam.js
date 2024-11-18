async function fetchData(url, kichwa, mwili) {
    try {
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type: application/json',
            'application_key: '
          },
          body: JSON.stringify( tittle: kichwa, text: mwili )
        });
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        document.getElementById('response1').innerHTML=JSON.stringify( data.copilot );
        document.getElementById('response2').innerHTML=JSON.stringify( data.synthesia );
        document.getElementById('response3').innerHTML=JSON.stringify( data.prictory )
    } catch (error) {
        console.error('Error fetching data:', error); 
    }
}

function submit() {
  preventDefault();
  
  const kichwa = document.getElementById('head').value;
  const mwili = document.getElementById('prompt').value;
  
  fetchData('https://api.textovideo.com/controller/process.php', kichwa, mwili);
}
