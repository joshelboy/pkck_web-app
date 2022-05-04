<template>
  <div>
    <apexcharts ref="exampleChart" width="500" height="350" type="line" :options="chartOptions" :series="series"></apexcharts>
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
          id: "strava-1y",
        },
        xaxis: {
          type: "datetime",
          categories: [],
        },
        tooltip: {
          x: {
            format: "dd MMM yyyy"
          },
        },
      },
      series: [],
    };
  },

  created: function() {
    this.getData();
    console.log("get");
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

              const tours = await axios.get('/api/tours/84d/' + row.strava_id);
              if(tours.status == 200){
                
                var startDate = new Date();
                var endDate = new Date();
                startDate.setDate(startDate.getDate() - 83);

                let loop = new Date(startDate);

                while (loop <= endDate) {
                  let dataDummy = { x: loop.toISOString().slice(0, 10), y: '0'}
                  dataStack.push(dataDummy);
                  let newDate = loop.setDate(loop.getDate() + 1);
                  loop = new Date(newDate);
                }

                for(let row of tours.data){

                  for (let stackRow of dataStack) {
                    if (row.date == stackRow.x) {
                      if (row.distance != null && row.distance != undefined) {
                        let km = row.distance / 1000;
                        stackRow.y = km.toFixed(2);
                      }
                    }
                  }

                }
              }

              let seriesObject = { name: row.name, data: dataStack }
              seriesStack.push(seriesObject);
              console.log(seriesStack)

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