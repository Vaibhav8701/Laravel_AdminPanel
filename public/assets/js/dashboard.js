/* SALES CHART */
new Chart(document.getElementById("salesChart"), {
    type: "line",
    data: {
        labels: ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"],
        datasets: [{
            label: "Revenue",
            data: [12000,17000,15000,21000,25000,28000,26000],
            borderColor: "#0d6efd",
            backgroundColor: "rgba(13,110,253,0.15)",
            borderWidth: 2,
            tension: 0.4,
            fill: true
        }]
    }
});

/* DONUT CHART */
new Chart(document.getElementById("donutChart"), {
    type: "doughnut",
    data: {
        labels: ["Electronics","Fashion","Home","Grocery"],
        datasets: [{
            data: [35,25,20,20],
            backgroundColor: ["#0d6efd","#28c76f","#ffc107","#ff5733"]
        }]
    }
});