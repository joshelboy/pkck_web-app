<template>
  <div>
    <p>GranFondos im Jahr 2022</p>
    <apexcharts ref="exampleChart" width="500" height="350" type="bar" :options="chartOptions" :series="series"></apexcharts>
  </div>
</template>

<script>
import axios from 'axios';
import VueApexCharts from "vue3-apexcharts";

export default {

  components: {
    apexcharts: VueApexCharts,
  },

  data: function() {
    return {
      chartOptions: {
        chart: {
          id: "strava-gf",
        },
        yaxis: {
          labels: {
            formatter: (value) => {
              return value.toFixed(0)
            },
          }
        },
      },
      series: [],
    };
  },

  created: function() {
    this.getData();
  },

  methods: {
      async getData() {
        try{

          const idList = await axios.get('/api/strava_user');
          //console.log(idList);
          if (idList.status == 200){
            let seriesStack = [];
            for (let row of idList.data){
              let dataStack = [];
              const tours = await axios.get('/api/tours/gf/' + row.strava_id);
              if(tours.status == 200){
                  let dataGF = { x: row.name, y: tours.data[0].count.toFixed(0)}
                  dataStack.push(dataGF);

              }

              let seriesObject = { name: row.name, data: dataStack }
              seriesStack.push(seriesObject);

            }
            this.series = seriesStack;
          }
        }
        catch (err){
          console.log(err);
        }
      },
  }
};
</script>

<style>

</style>