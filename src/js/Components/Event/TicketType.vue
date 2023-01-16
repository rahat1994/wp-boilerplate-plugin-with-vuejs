<template>
  <div>
    
    <div :key="index" v-for="(ticketType, index) in ticketTypes" style="padding: 20px;">
      <hr/>
      <el-form :inline="true" :model="formInline" class="demo-form-inline">

        <el-input size="mini" v-model="ticketType.name" placeholder="Tier Name"></el-input>
        <el-input size="mini" v-model="ticketType.price" placeholder="Tier Price"></el-input>  

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