$(document).ready(()=>{
  subscriptionTypeList();
})

async function subscriptionTypeList(){
  try {
    const res = await $.ajax({
      url: '../app/subscription/types_web',
      type: 'GET',
      data: {},
      dataType: 'html'
    });
    $("#list_subscription_types").html(res);
  }catch (error) {
    console.log(error)
  }
}