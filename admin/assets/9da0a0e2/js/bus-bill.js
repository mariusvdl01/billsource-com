function LineTotal0()
{
   var qty=document.getElementById("billlineqty0");
   var up=document.getElementById("billlineup0");
   var amnt=document.getElementById("billlineamnt0");
   var hamnt=document.getElementById("hbilllineamnt0");


   hamnt.value = Math.round((Number(qty.value)*Number(up.value))*100)/100;
   amnt.value = hamnt.value;
   SubTotal();
   return false;
}

function LineTotal1()
{
   var qty=document.getElementById("billlineqty1");
   var up=document.getElementById("billlineup1");
   var amnt=document.getElementById("billlineamnt1");
   var hamnt=document.getElementById("hbilllineamnt1");


   hamnt.value = Math.round((Number(qty.value)*Number(up.value))*100)/100;
   amnt.value = hamnt.value;
   SubTotal();
   return false;
}

function LineTotal2()
{
   var qty=document.getElementById("billlineqty2");
   var up=document.getElementById("billlineup2");
   var amnt=document.getElementById("billlineamnt2");
   var hamnt=document.getElementById("hbilllineamnt2");


   hamnt.value = Math.round((Number(qty.value)*Number(up.value))*100)/100;
   amnt.value = hamnt.value;
   SubTotal();
   return false;
}

function LineTotal3()
{
   var qty=document.getElementById("billlineqty3");
   var up=document.getElementById("billlineup3");
   var amnt=document.getElementById("billlineamnt3");
   var hamnt=document.getElementById("hbilllineamnt3");


   hamnt.value = Math.round((Number(qty.value)*Number(up.value))*100)/100;
   amnt.value = hamnt.value;
   SubTotal();
   return false;
}

function LineTotal4()
{
   var qty=document.getElementById("billlineqty4");
   var up=document.getElementById("billlineup4");
   var amnt=document.getElementById("billlineamnt4");
   var hamnt=document.getElementById("hbilllineamnt4");


   hamnt.value = Math.round((Number(qty.value)*Number(up.value))*100)/100;
   amnt.value = hamnt.value;
   SubTotal();
   return false;
}

function isEmpty(str) 
{
    return (!str || 0 === str.length);
}

function SubTotal()
{
   var hamnt0=document.getElementById("hbilllineamnt0");
   var hamnt1=document.getElementById("hbilllineamnt1");
   var hamnt2=document.getElementById("hbilllineamnt2");
   var hamnt3=document.getElementById("hbilllineamnt3");
   var hamnt4=document.getElementById("hbilllineamnt4");
   var busvat=document.getElementById("busvat");
   var vat=document.getElementById("vat");
   var dscnt=document.getElementById("dscnt");
   var dscntval = 0-Number(dscnt.value);
   dscntval = dscntval;
   var st=document.getElementById("billamnt");
   var dst=document.getElementById("dbillamnt");
   var tot=document.getElementById("tot");
   st.value = Math.round(((Number(hamnt0.value)+Number(hamnt1.value)+Number(hamnt2.value)+Number(hamnt3.value)+Number(hamnt4.value))+(dscntval))*100)/100;
   dst.value = st.value;
   if(!isEmpty(busvat.value))
   {
       vat.value = Math.round((Number(st.value)*.14*100))/100;
       tot.value = Math.round((Number(st.value)*1.14*100))/100;
   }
   else
   { 
       vat.value = "N/A";
       tot.value = st.value;
   }
   return false;
}

function PromtDelete()
{
    return confirm("Are you sure you want to delete the selected item?");
}

function Load()
{
    LineTotal0();
    LineTotal1();
    LineTotal2();
    LineTotal3();
    LineTotal4();
    return false;
}
