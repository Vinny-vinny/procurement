<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box box-solid box-default">
                <div class="box-header with-border b-header">
                    <h3 class="box-title esl-title">EXPRESS SHIPPING & LOGISTICS (E.A) LTD</h3>
                </div>
                <div class="box-body">
                  <h3 class="esl-title memo">Memo</h3>
   <br>        
<div class="row">
  <div class="col-md-2 font-s"><b>To</b></div>
  <div class="col-md-4 col-md-offset-6 font-s"> :All Staff</div>
</div>
<br>
<div class="row">
  <div class="col-md-2 font-s"><b>From</b></div>
  <div class="col-md-4 col-md-offset-6 font-s"> :Procurement Committee</div>
</div>
<br>
<div class="row">
  <div class="col-md-2 font-s"><b>CC</b></div>
  <div class="col-md-4 col-md-offset-6 font-s"> :Finance Department</div>
</div>
<br>
<div class="row">
  <div class="col-md-2 font-s"><b>Date</b></div>
  <div class="col-md-4 col-md-offset-6 font-s"> :23rd November 2017</div>
</div>
<br>
<div class="row">
  <div class="col-md-2 font-s"><b>Subject</b></div>
  <div class="col-md-4 col-md-offset-6 font-s"> :<b>ASSETS FOR SALE</b></div>
</div>
<hr>
<p>You are kindly invited to submit your bids for assets listed hereunder currently in disposal process.</p>
<div>
  <ol type="1"> 
  <li v-for="asset in asset_details" :key="asset.id">{{asset.description}};Asset Code-{{asset.code}}</li>  
</ol>
</div>

  <p>For viewing, please contact the Procurement Officer within normal office hours.</p>
<p>Interested staff please submit your bids by {{all_data.date_closed}} and deposit in the bid box.</p>
<p>Kindly note that the company disposal policy and procedures subsists i.e.  “as- is where -is basis”.</p>
<p>Bids will be opened on {{all_data.date_opened}} at the ESL boardroom.</p>
<p>NB: This is an internal invitation and canvassing will lead to automatic disqualification.</p>
<p>Thank you.</p>
<p>With kind regards,</p>
<p>Procurement Officer:________________________________</p>
<p>Procurement Chairman:________________________________</p>
                </div>
            <div class="form-group actions print">
            <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
             <button type="button" class="btn btn-success" @click="print"><i class="fa fa-print"></i> Print</button>
            </div>
            </div>

        </section>

    </div>
</template>

<script>
    export default {
        
        data(){
            return {
               all_data:{},
               asset_details:[]             
            }
        },
        created(){
       this.listen();
        },
        methods:{
           print(){
                window.print();
            },
          cancel(){
            eventBus.$emit('cancel');
          },
          listen(){
          this.all_data = this.$store.state.disposals;
          let asset_info = this.all_data.asset_details.map(asset => {
            return asset.asset_id;
          })

        //  console.log(asset_info)
          axios.get('machines')
          .then(res => {
            res.data.forEach(m => {
              if(asset_info.includes(m.id)){
                this.asset_details.push({
                  'id': m.id,
                  'description': m.description,
                  'code': m.code
                });
              }             
            })

          })
          }

        }        
       
    }
</script>

<style scoped>
.font-s{
  font-size:20px
}
.b-header,.memo{
    text-align:center;
}
.esl-title{
    font-weight:900;
    font-size:26px;
}
hr{
  position: relative; 
  top: 20px; 
  border: none; 
  height: 2px; 
  background: black; 
  margin-bottom: 50px; 
}
.actions{
  padding-left:20px;
}

@media print {
        .print {
            display: none;
        }
       .main-footer{
         display: none;
       }
    }
</style>
