<template>
  <div>
    
    <div :key="index" v-for="(ticketType, index) in ticketTypes" style="padding: 20px;">
      <hr/>
      <el-form :inline="true" :model="formInline" class="demo-form-inline">

        <el-form-item label="Seat/Ticket Name">
          <el-input size="mini" v-model="ticketType.name" placeholder="Tier Name"></el-input>
        </el-form-item>

        <el-form-item label="Price">
          <el-input-number size="mini" v-model="ticketType.price" placeholder="Tier Price"></el-input-number>  
        </el-form-item>

        <el-form-item label="Seat Limit">
          <el-input-number size="mini" v-model="ticketType.limit" placeholder="seat limit"></el-input-number>  
        </el-form-item>

        <el-form-item label="Ticket Description">
          <el-input   type="textarea" autosize placeholder="Please input" v-model="ticketType.description"></el-input>
        </el-form-item>
        
        
        <br>
        
        

        <el-button v-if="index === ticketTypes.length - 1" size="mini" type="primary" @click="addTicketTypeAtTheEnd" icon="el-icon-plus">Add more</el-button>
        <el-button v-if="index !== 0 || ticketTypes.length > 1" size="mini" type="danger" @click="deleteTask(index)" icon="el-icon-close" >Remove</el-button>

      </el-form>
    </div>
  </div>

  


</template>

<script>
export default {
  name: 'TicketType',

  data() {
    return {
      formInline: {
        name: 'V.I.P Tickets',
        price: 500
      }
    }
  },
  methods: {
      onSubmit() {
        console.log(this.ticketTypes);
      },
      deleteTask(index){
        this.$emit('delete-ticket-type', index);
      }
  },
  props:[
    'ticketTypes',
    'removeTicketTypeAtIndex',
    'addTicketTypeAtTheEnd'
  ]
}
</script>

<style scoped>
button {
  font-weight: bold;
}
</style>