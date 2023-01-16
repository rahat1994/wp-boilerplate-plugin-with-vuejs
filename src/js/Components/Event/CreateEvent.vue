<template>
    <div>
        <br>
        <el-header>
          <el-row type="flex" justify="end">
            <el-col :span="24">
              <h2>Create New Event</h2>                      
            </el-col>          
          </el-row>            
        </el-header>        
        <el-main>

            <el-row type="flex" justify="start">
                <el-col :span="12">
                    
                    <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="120px" class="demo-ruleForm">
                        <el-form-item label="Event Name" prop="name">
                            <el-input v-model="ruleForm.name"></el-input>
                        </el-form-item>

                        <el-form-item label="Event Type" prop="region">
                            <el-select v-model="ruleForm.region" placeholder="Event Type">
                                <el-option label="Online" value="online"></el-option>
                                <el-option label="Offline" value="offline"></el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="Event time" required>
                            <el-col :span="11">
                                <el-form-item prop="date1">
                                    <el-date-picker type="datetime" placeholder="Pick a Date and Time" v-model="ruleForm.date1" style="width: 100%;"></el-date-picker>
                                </el-form-item>
                            </el-col>
                        </el-form-item>


                        <el-form-item label="Free to join" prop="delivery">
                            <el-switch v-model="ruleForm.openEvent"></el-switch>
                        </el-form-item>

                        <el-form-item v-if="ruleForm.openEvent" label="Ticket Tiers" prop="delivery">
                            <ticket-type 
                              @delete-ticket-type="removeTicketTypeAtIndex"
                              :ticketTypes="ruleForm.ticketTypes" 
                              :removeTicketTypeAtIndex="removeTicketTypeAtIndex"
                              :addTicketTypeAtTheEnd="addMoreTicketTypesAtTheEnd"
                            />
                        </el-form-item>                        
                        <el-form-item label="Description" prop="desc">
                            <el-input type="textarea" v-model="ruleForm.desc"></el-input>
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" @click="submitForm('ruleForm')">Create</el-button>
                        </el-form-item>
                    </el-form>
                </el-col>          
            </el-row>

        </el-main>

        <el-footer>
            <p>This is a footer</p>
        </el-footer>
    </div>
</template>
<script>

import TicketType from "./TicketType.vue";

export default {
    name: 'CreateEvent',
    components:  {
        TicketType
    },
    data() {
      return {
        ruleForm: {
          eventName: '',
          eventRegion: '',
          eventDate: '',
          openEvent:true,
          type: [],
          resource: '',
          desc: '',
          ticketTypes:[
            {
              name: "V.I.P",
              price: 500
            },
            {
              name: "V.V.I.P",
              price: 1500
            }
          ],
        },
        rules: {
          name: [
            { required: true, message: 'Please input Activity name', trigger: 'blur' },
            { min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur' }
          ],
          region: [
            { required: true, message: 'Please select Activity zone', trigger: 'change' }
          ],
          date1: [
            { type: 'date', required: true, message: 'Please pick a date', trigger: 'change' }
          ],
          date2: [
            { type: 'date', required: true, message: 'Please pick a time', trigger: 'change' }
          ],
          type: [
            { type: 'array', required: true, message: 'Please select at least one activity type', trigger: 'change' }
          ],
          resource: [
            { required: true, message: 'Please select activity resource', trigger: 'change' }
          ],
          desc: [
            { required: true, message: 'Please input activity form', trigger: 'blur' }
          ]
        }
      };
    },
    methods: {
      submitForm(formName) {
        console.log(this.ruleForm);
        return;
        this.$refs[formName].validate((valid) => {
          if (valid) {
            alert('submit!');
          } else {
            console.log('error submit!!');
            return false;
          }
        });
      },
      resetForm(formName) {
        this.$refs[formName].resetFields();
      },
      addMoreTicketTypesAtTheEnd(){
        this.ruleForm.ticketTypes.push({
            name: "",
            price: 0
          });
        return;
      },
      removeTicketTypeAtIndex(index){
        console.log('task', index);
        this.ruleForm.ticketTypes.splice(index, 1)
      }
    }
}
</script>

