function Totals()
{
   var home1=document.getElementById("home1");
   var home2=document.getElementById("home2");
   var home3=document.getElementById("home3");

   var vehicle1=document.getElementById("vehicle1");
   var vehicle2=document.getElementById("vehicle2");
   var craft=document.getElementById("craft");

   var policies=document.getElementById("policies");
   var investments=document.getElementById("investments");
   var savings=document.getElementById("savings");
   var y=document.getElementById("assets");
   var z=document.getElementById("hassets");
   y.value = Math.round((Number(home1.value)+Number(home2.value)+Number(home3.value)
   +Number(vehicle1.value)+Number(vehicle2.value)+Number(craft.value)+Number(policies.value)
   +Number(investments.value)+Number(savings.value))*100)/100;
   z.value = y.value;
   return false;
}

function TotalLoan()
{
   var bond1=document.getElementById("bond1");
   var bond2=document.getElementById("bond2");
   var bond3=document.getElementById("bond3");

   var carloan1=document.getElementById("carloan1");
   var carloan2=document.getElementById("carloan2");
   var craftloan=document.getElementById("craftloan");

   var stloan=document.getElementById("stloan");
   var bills=document.getElementById("bills");
   var y=document.getElementById("liabilities");
   var z=document.getElementById("hliabilities");
   y.value = Math.round((Number(bond1.value)+Number(bond2.value)+Number(bond3.value)
   +Number(carloan1.value)+Number(carloan2.value)+Number(craftloan.value)+Number(stloan.value)
   +Number(bills.value))*100)/100;
   z.value = y.value;
   return false;
}

function Totalinc()
{
	   var netinc=document.getElementById("netinc");
	   var expense=document.getElementById("netexp");
	   var surp=document.getElementById("surp");
	   var hsurp=document.getElementById("hsurp");
	   hsurp.value= Math.round(Number(netinc.value)-Number(expense.value));
	   surp.value = hsurp.value;
}

function load()
{
   Totals();
   TotalLoan();
   Totalinc();
}
