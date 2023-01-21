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

                        <el-form-item label="Event Type" prop="eventType">
                            <el-select v-model="ruleForm.eventType" placeholder="Event Type">
                                <el-option label="Online"  value="Online" ></el-option>
                                <el-option label="Offline" value="Offline"></el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="Event time" required>
                            <el-col :span="11">
                                <el-form-item prop="eventDate">
                                    <el-date-picker type="datetime" placeholder="Pick a Date and Time" v-model="ruleForm.eventDate" style="width: 100%;"></el-date-picker>
                                </el-form-item>
                            </el-col>
                        </el-form-item>


                        <el-form-item label="Free to join" prop="delivery" >
                            <el-switch v-model="ruleForm.openEvent" @change="handleOpenEventChange"></el-switch>
                        </el-form-item>

                        <el-form-item v-if="!ruleForm.openEvent" label="Ticket Tiers" prop="delivery">
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
import swal from 'sweetalert';

export default {
    name: 'CreateEvent',
    components:  {
        TicketType
    },
    data() {
      return {
        ruleForm: {
          eventName: '',
          eventType: "Online",
          eventDate: '',
          openEvent: true,
          type: [],
          resource: '',
          desc: '',
          ticketTypes:[
            {
              name: "",
              price: 0,
              limit: 0,
              description:""
            }
          ],
        },
        rules: {
          name: [
            { required: true, message: 'Please input Event name', trigger: 'blur' },
            { min: 8, message: 'Length should be at laast 8 characters long', trigger: 'blur' }
          ],
          eventType: [
            { required: true, message: 'Please select event type', trigger: 'change' }
          ],
          eventDate: [
            { type: 'date', required: true, message: 'Please pick a date', trigger: 'change' }
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

        var formData = {
            'route' : 'create_event',
            'data' : {
                'name': this.ruleForm.name,
                'is_online': (this.ruleForm.eventType === 'Online') ? 1 : 0,
                'description': this.ruleForm.desc,
                'social_media':'[{}]',
                'form_fields':'[{}]',
                'ticket_types': (this.ruleForm.openEvent) ? this.ruleForm.ticketTypes : '[]'
            }
        }
        
        this.$refs[formName].validate(async (valid) =>  {
          if (valid) {
            
            let data = await this.$adminPost(formData);

            if(data.success === true){
              swal({
                title: data.data.data,
                icon: "success",
                button: {
                  text: "OK",
                  value: true,
                  visible: true,
                  className: "",
                  closeModal: true,
                }
              }).then((decision) => {
                if(decision === true){
                  this.resetForm(formName);
                } else{

                }              
              });
            } else{
              swal({
                title: data.data,
                icon: "error",
                button: {
                  text: "OK",
                  value: true,
                  visible: true,
                  className: "",
                  closeModal: true,
                }
              });
            }

          } else {
            swal({
                title: "Please fill-up the form properly",
                icon: "error",
                button: {
                  text: "OK",
                  value: true,
                  visible: true,
                  className: "",
                  closeModal: true,
                }
              });
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
      },
      handleOpenEventChange(event){
        console.log(this.ruleForm.openEvent);
      }
    }
}
</script>

