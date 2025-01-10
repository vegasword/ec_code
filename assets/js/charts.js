async function buildRadarChart() {
  try {
    var categoriesResponse = await fetch('https://localhost:8000/books/categories', {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' },
        mode: 'cors',
    });
    
    if (!categoriesResponse.ok) throw new Error(categoriesResponse.statusText);
    
    var categories = await categoriesResponse.json();
    console.log(categories);
    
    var booksReadResponse = await fetch('https://localhost:8000/books/read', {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' },
        mode: 'cors',
    });
    
    if (!booksReadResponse.ok) throw new Error(booksReadResponse.statusText);
    
    var booksRead = await booksReadResponse.json();
    console.log(booksRead);
    
    const categoryCounts = {};
    categories.forEach(category => { categoryCounts[category.name] = 0; });

    booksRead.forEach(bookRead => {
      if (categoryCounts[bookRead.category] !== undefined) {
        categoryCounts[bookRead.category]++;
      }
    });
    console.log(categoryCounts);
    
    var chartOptions = {
      chart: {
        type: 'radar',
        animations: { enabled: false },
        toolbar: { show: false },
        selection: { enabled: false },
        zoom: { enabled: false }
      },
      tooltip: {
        enabled: false
      },
      series: [{ data: Object.values(categoryCounts) }],
      xaxis: {
        categories: Object.keys(categoryCounts)
      }
    }

    var radarChart = new ApexCharts(document.querySelector("#chart"), chartOptions);
    radarChart.render();
  } catch(error) {
    console.error(error);
  }
}

buildRadarChart();