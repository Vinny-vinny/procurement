<template>
    <aside class="main-sidebar" id="sidebar-wrapper">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="http://infyom.com/images/logo/blue_logo_150x150.jpg" class="img-circle"
                         alt="User Image"/>
                </div>
                <div class="pull-left info">
                    <p>{{username()}}</p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- Sidebar Menu -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header"></li>

                <!-- Optionally, you can add icons to the links -->
                    <li class="treeview">
                    <a href="#"><i class="fa fa-money"></i> <span>Budgeting</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><router-link to="/budget" class="fa fa-circle-o"> Department Budget</router-link></li>                   
                    </ul>
                </li>
                <li><router-link to="/requisitions"><i class="fa fa-database"></i> <span>Requisitions</span></router-link></li>

                 <li class="treeview">
                    <a href="#"><i class="fa fa-shopping-basket"></i> <span>Purchases</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><router-link to="/enquiry" class="fa fa-circle-o"> Purchase Enquiry</router-link></li> 
                        <li><router-link to="/purchase-order" class="fa fa-circle-o"> Purchase Order</router-link></li>                    
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-shopping-bag"></i> <span>Quotations</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><router-link to="/quotations" class="fa fa-circle-o"> Receive Quotation</router-link></li> 
                        <li><router-link to="/approve-quotations" class="fa fa-circle-o"> Approve Quotations</router-link></li>                   
                    </ul>
                </li>
                  <li class="treeview">
                    <a href="#"><i class="fa fa-recycle"></i> <span>Asset Disposal</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                 </a>
                    <ul class="treeview-menu">
                        <li><router-link to="/asset-disposal" class="fa fa-circle-o"> Disposals</router-link></li> 
                        <li><router-link to="/bidding" class="fa fa-circle-o"> Biddings</router-link></li>
                        <li><router-link to="/bid-award" class="fa fa-circle-o"> Bid Awards</router-link></li>                   
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#"><i class="fa fa-cogs"></i> <span>Settings</span>
                        <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><router-link to="/users" class="fa fa-circle-o"> Users</router-link></li>
                          <li><router-link to="/suppliers" class="fa fa-circle-o"> Suppliers</router-link></li>
                        <li><router-link to="/categories" class="fa fa-circle-o"> Categories</router-link></li>
                        <li><router-link to="/requisition-type" class="fa fa-circle-o"> Requisition Types</router-link></li>
                        <li><router-link to="/departments" class="fa fa-circle-o"> Departments</router-link></li>
                        <li><router-link to="/priorities" class="fa fa-circle-o"> Priorities</router-link></li>
                        <li><router-link to="/projects" class="fa fa-circle-o"> Projects</router-link></li>
                        <li><router-link to="/all-assets" class="fa fa-circle-o"> Assets</router-link></li>
                        <li><router-link to="/items" class="fa fa-circle-o"> Stock Items</router-link></li>
                         <li><router-link to="/services" class="fa fa-circle-o"> Services</router-link></li>
                           <li><router-link to="/payment-modes" class="fa fa-circle-o"> Payment Modes</router-link></li>
                    </ul>
                </li>

            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>
</template>
<script>
    export default {
      methods:{
          username(){
              return User.name();
          }
      }
    }
</script>

<style scoped>

</style>
