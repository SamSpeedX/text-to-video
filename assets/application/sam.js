async function fetchData(url, kichwa, mwili) {
    try {
        
        document.getElementById('response3').innerHTML = "Generating video... Please wait.";
        document.getElementById('response1').classList.remove('show');
        document.getElementById('response2').classList.remove('show');
        document.getElementById('response3').classList.add('show');

        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'application_key': '' // Ensure you replace this with your actual API key if needed
          },
          body: JSON.stringify({ tittle: kichwa, text: mwili })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();
        displayResults(data);
    } catch (error) {
        console.error('Error fetching data:', error);
        document.getElementById('response3').innerHTML = "An error occurred. Please try again later.";
    }
}

function displayResults(data) {
    
    document.getElementById('response3').classList.remove('show');

    if (data.copilot) {
        document.getElementById('response1').innerHTML = JSON.stringify(data.copilot);
        document.getElementById('response1').classList.add('show');
    } else {
        document.getElementById('response1').innerHTML = "No result from Copilot.";
        document.getElementById('response1').classList.add('show');
    }
    
    if (data.synthesia) {
        document.getElementById('response2').innerHTML = JSON.stringify(data.synthesia);
        document.getElementById('response2').classList.add('show');
    } else {
        document.getElementById('response2').innerHTML = "No result from Synthesia.";
        document.getElementById('response2').classList.add('show');
    }

    if (data.prictory) {
        document.getElementById('response3').innerHTML = JSON.stringify(data.prictory);
        document.getElementById('response3').classList.add('show');
    } else {
        document.getElementById('response3').innerHTML = "No result from Prictory.";
        document.getElementById('response3').classList.add('show');
    }
}

function submit(event) {
    event.preventDefault(); 
    
    const kichwa = document.getElementById('head').value;
    const mwili = document.getElementById('text').value;
    
    if (!kichwa || !mwili) {
        alert("Please fill in both the heading and your video idea.");
        return;
    }

    fetchData('https://api.textovideo.com/controller/process.php', kichwa, mwili);
}
