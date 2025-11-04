<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Line Production Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://d3js.org/d3.v3.min.js"></script>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Open Sans', sans-serif;
      background: linear-gradient(to right, #f0f4f8, #d9e4f5);
      color: #333;
    }

    header {
      background: #0d6efd;
      color: white;
      padding: 1.5rem 2rem;
      text-align: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .line-name {
      font-size: 2rem;
      font-weight: 600;
      animation: fadeInDown 1s ease-in-out;
    }

    .efficiency-value {
      font-size: 1.2rem;
      font-weight: 500;
      animation: fadeIn 2s ease-in-out;
    }

    @keyframes fadeInDown {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .dashboard {
      padding: 2rem;
    }

    .gauge-wrapper {
      width: 500px;
      height: 340px;
      text-align: center;
    }

    .gauge-label {
      margin-top: 0.5rem;
      font-weight: 600;
      color: #0d6efd;
    }

    .gauge .background {
      fill: #ddd;
    }

    .gauge .value-text {
      fill: #212529;
      font-size: 1.5rem;
      font-weight: bold;
    }

    .section-title {
      font-weight: 600;
      font-size: 1.25rem;
      margin-bottom: 1rem;
      text-align: center;
    }

    .card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
      padding: 1.5rem;
      margin-bottom: 2rem;
    }

    footer {
      text-align: center;
      padding: 1rem;
      background: #eaeaea;
      font-size: 0.9rem;
      color: #555;
    }

    .prediction-box {
      background: #fff6e0;
      border-left: 6px solid #ffc107;
      padding: 1rem 1.5rem;
      font-size: 1rem;
      font-weight: 500;
      color: #856404;
      border-radius: 0.5rem;
    }

    #gauges {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 2rem;
    }

  </style>
</head>
<body>

<header>
  <div class="line-name">🧵 Line 7 - Apparel Production</div>
  <div class="efficiency-value">🔧 Real-time Efficiency Monitoring</div>
</header>

<section class="dashboard container-fluid">
  <!-- Gauge Section -->
  <div class="card">
    <div class="section-title">🟢 Machine Efficiency Gauges</div>
    <div id="gauges"></div>
  </div>
 <div class="row">
 <div class="col-md-6">
      <!-- Highest Production -->
    <div class="card">
        <div class="section-title" style="font-size:60px">🏆 Highest Production Line</div>
        <div class="text-center fs-5 fw-semibold text-success" id="highestLine" style="font-size:80px!important">Loading...</div>
    </div>
</div>
  <!-- Prediction -->
 <div class="col-md-6">
  <div class="card">
    <div class="section-title" style="font-size:60px">🔮 Predictive Insight</div>
    <div class="prediction-box" id="predictionText" style="font-size:60px">
      Prediction based on current trends will appear here...
    </div>
  </div>
  </div>
  </div>
</section>

<footer>
  &copy; 2025 Ken Global Designs Pvt. Ltd. | Powered by D3.js & Bootstrap
</footer>

<script>
  const gaugeCount = 4;
  const gaugeSize = 150;
  const thickness = 60;
  const updateInterval = 2500;

  const colorScale = d3.scale.linear()
    .domain([0, 50, 100])
    .range(['#db2828', '#fbbd08', '#21ba45']);

  const arc = d3.svg.arc()
    .innerRadius(gaugeSize - thickness)
    .outerRadius(gaugeSize)
    .startAngle(-Math.PI / 2);

  function createGauge(containerId, gaugeIndex, label) {
    const wrapper = d3.select(containerId)
      .append("div")
      .attr("class", "gauge-wrapper");

    const svg = wrapper.append("svg")
      .attr("width", gaugeSize * 2)
      .attr("height", gaugeSize + 20)
      .attr("class", "gauge");

    const chart = svg.append("g")
      .attr("transform", "translate(" + gaugeSize + "," + gaugeSize + ")");

    chart.append("path")
      .datum({ endAngle: Math.PI / 2 })
      .attr("class", "background")
      .attr("d", arc);

    const foreground = chart.append("path")
      .datum({ endAngle: -Math.PI / 2 })
      .style("fill", "#db2828")
      .attr("d", arc);

    const valueGroup = svg.append("g")
      .attr("transform", "translate(" + gaugeSize + "," + (gaugeSize * 0.9) + ")");

    const valueText = valueGroup.append("text")
      .text("0%")
      .attr("text-anchor", "middle")
      .attr("class", "value-text");

    // Add Label Below Gauge
    wrapper.append("div")
      .attr("class", "gauge-label")
      .text(label);

    return { foreground, valueText, gaugeIndex, label, value: 0 };
  }

  function updateGauge(gauge, newValue) {
    newValue = d3.format('.1f')(newValue);
    const endAngle = newValue / 100 * Math.PI - Math.PI / 2;
    gauge.value = parseFloat(newValue);

    gauge.foreground.transition()
      .duration(800)
      .style("fill", colorScale(newValue))
      .attrTween("d", function (d) {
        const interpolate = d3.interpolate(d.endAngle, endAngle);
        return function (t) {
          d.endAngle = interpolate(t);
          return arc(d);
        };
      });

    gauge.valueText.transition()
      .duration(800)
      .tween("text", function () {
        const interpolate = d3.interpolate(this.textContent, newValue);
        const textEl = this;
        return function (t) {
          textEl.textContent = d3.format('.1f')(interpolate(t)) + "%";
        };
      });
  }

  const gauges = [];
  const labels = ["Line 1", "Line 2", "Line 3", "Line 4"];
  for (let i = 0; i < gaugeCount; i++) {
    const g = createGauge("#gauges", i, labels[i]);
    gauges.push(g);
  }

  function refreshAll() {
    gauges.forEach(g => {
      const randomValue = Math.random() * 100;
      updateGauge(g, randomValue);
    });

    // Update highest production
    setTimeout(() => {
      const maxGauge = gauges.reduce((a, b) => (a.value > b.value ? a : b));
      document.getElementById("highestLine").textContent = `${maxGauge.label} (${maxGauge.value.toFixed(1)}%)`;

      // Prediction Logic
      let prediction = "Production is stable.";
      if (maxGauge.value > 90) prediction = "High output expected today!";
      else if (maxGauge.value > 70) prediction = "Above average performance.";
      else if (maxGauge.value > 50) prediction = "Average production level.";
      else prediction = "Low performance. Monitor closely.";

      document.getElementById("predictionText").textContent = prediction;
    }, 900);
  }

  refreshAll();
  setInterval(refreshAll, updateInterval);
</script>

</body>
</html>
