// Admin -------- users-chart-script
// VendorAdmin -------- orders-count-chart-script
createdoughnut(doughnutlabels, doughnutdata);
$("#doughnutyear").on("change", function() {
  "use strict";
  $.ajax({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    },
    url: $("#doughnutyear").attr("data-url"),
    method: "GET",
    data: {
      doughnutyear: $("#doughnutyear").val()
    },
    dataType: "JSON",
    success: function(data) {
      createdoughnut(data.doughnutlabels, data.doughnutdata);
    },
    error: function(data) {
      toastr.error(wrong);
      return false;
    }
  });
});
function createdoughnut(doughnutlabels, doughnutdata) {
  "use strict";

  const chartdata = {
    labels: doughnutlabels,

    datasets: [
      {
        label: "Total : ",
        backgroundColor: [
          "rgba(54, 162, 235, 0.4)",
          "rgba(255, 150, 86, 0.4)",
          "rgba(140, 162, 198, 0.4)",
          "rgba(255, 206, 86, 0.4)",
          "rgba(255, 99, 132, 0.4)",
          "rgba(255, 159, 64, 0.4)",
          "rgba(255, 205, 86, 0.4)",
          "rgba(75, 192, 192, 0.4)",
          "rgba(54, 170, 235, 0.4)",
          "rgba(153, 102, 255, 0.4)",
          "rgba(201, 203, 207, 0.4)",
          "rgba(255, 159, 64, 0.4)"
        ],
        borderColor: [
          "rgba(54, 162, 235, 1)",
          "rgba(255, 150, 86, 1)",
          "rgba(140, 162, 198, 1)",
          "rgba(255, 206, 86, 1)",
          "rgba(255, 99, 132, 1)",
          "rgba(255, 159, 64, 1)",
          "rgba(255, 205, 86, 1)",
          "rgba(75, 192, 192, 1)",
          "rgba(54, 170, 235, 1)",
          "rgba(153, 102, 255, 1)",
          "rgba(201, 203, 207, 1)",
          "rgba(255, 159, 64, 1)"
        ],
        borderWidth: 2,
        hoverOffset: 5,
        data: doughnutdata
      }
    ]
  };

  const config = {
    type: "pie",

    data: chartdata,

    options: {
      plugins: {
        legend: {
          display: false
        }
      }
    }
  };

  if (doughnut != null) {
    doughnut.destroy();
  }

  if (document.getElementById("doughnut")) {
    doughnut = new Chart(document.getElementById("doughnut"), config);
  }
}
// Admin ------ revenue-by-plans-chart-script
// vendorAdmin ------ revenue-by-orders-script
createrevenueChart(labels, revenuedata);
$("#revenueyear").on("change", function() {
  "use strict";
  $.ajax({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    },
    url: $("#revenueyear").attr("data-url"),
    method: "GET",
    data: {
      revenueyear: $("#revenueyear").val()
    },
    dataType: "JSON",
    success: function(data) {
      createrevenueChart(data.revenuelabels, data.revenuedata);
    },
    error: function(data) {
      toastr.error(wrong);
      return false;
    }
  });
});
function createrevenueChart(labels, revenuedata, year) {
  "use strict";
  const chartdata = {
    labels: labels,
    datasets: [
      {
        label: "Revenue ",
        fill: {
          target: "origin",
          above: "#E4EDD5"
        },
        borderColor: "#E4EDD5",
        tension: 0.1,
        pointBackgroundColor: "#96C13E",
        pointBorderColor: "#96C13E",
        data: revenuedata
      }
    ]
  };
  const config = {
    type: "line",
    data: chartdata,
    options: {
        plugins: {
          legend: {
            display: false
          }
        }
      }
  };
  if (revenuechart != null) {
    revenuechart.destroy();
  }
  if (document.getElementById("revenuechart")) {
    revenuechart = new Chart(document.getElementById("revenuechart"), config);
  }
}
