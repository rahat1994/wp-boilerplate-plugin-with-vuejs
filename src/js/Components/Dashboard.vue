<template>
    <div>
        <br>
        <el-header>
          <el-row type="flex" justify="end">
            <el-col :span="18">
              <h2>Events</h2>                      
            </el-col>
            <el-col :span="6" push="3">
              <el-button @click="ajax()" type="primary" icon="el-icon-plus" round>Add New Event</el-button>
            </el-col>            
          </el-row>            
        </el-header>        
        <el-main>
          <el-table
              :data="formList"
              :fit="true"
              :default-sort = "{prop: 'id', order: 'descending'}"
              style="width: 100%">
              <el-table-column
                prop="id"
                label="Id"
                sortable
                width="80">
              </el-table-column>
              <el-table-column
                style="font-weight: 800;"
                prop="name"
                label="Event Name"
                :formatter="formatter"
                >
              </el-table-column>
              <el-table-column
                prop="bookings"
                label="Booking"
                width="80">
              </el-table-column>

              <el-table-column
                prop="views"
                label="Views"
                width="80">
              </el-table-column>

              <el-table-column
                prop="conversion"
                label="Conversion"
                width="150">
              </el-table-column>
            </el-table>
        </el-main>

        <el-footer>
            <p>This is a footer</p>
        </el-footer>
    </div>
</template>
<script>
import EventNameCell from "./TableComponents/EventNameCell.vue";

export default {
    name: 'Dashboard',
    components:{
      EventNameCell
    },
    data(){
        return {
          formList:[
            {
              id: 1,
              name: "TEDx SUST",
              shortCode:"[Reventz id='1']",
              bookings: 0,
              views: 0,
              conversion: 10
            },
            {
              id: 1,
              name: "TEDx Leading University",
              shortCode:"[Reventz id='1']",
              bookings: 0,
              views: 0,
              conversion: 10
            },
            {
              id: 1,
              name: "TEDx Metropolitan University",
              shortCode:"[Reventz id='1']",
              bookings: 0,
              views: 0,
              conversion: 10
            }
          ]
        }
    },
    methods: {
        reDirrect() {
          window.open(this.vueJs, "_blank");    
        },
        formatter(row, column) {
          return row.name;
        },
        ajax(){
          var option = {
            route: 'get_data'
          };
          this.$adminGet(option).then((data) => {
              console.log(data);
          });
        }
    }
}
</script>
<style>
  .el-row {
    margin-bottom: 20px;
    &:last-child {
      margin-bottom: 0;
    }
  }
  .el-col {
    border-radius: 4px;
  }
  .bg-purple-dark {
    background: #99a9bf;
  }
  .bg-purple {
    background: #d3dce6;
  }
  .bg-purple-light {
    background: #e5e9f2;
  }
  .grid-content {
    border-radius: 4px;
    min-height: 36px;
  }
  .row-bg {
    padding: 10px 0;
    background-color: #f9fafc;
  }
</style>