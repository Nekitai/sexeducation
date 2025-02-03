<?php
 @session_start();
 if (!isset($_SESSION["login"]) && $_SESSION["login"] !=true){
    header("location:../index.php");
 }
 $sql = "SELECT * FROM tbl_user LIMIT 1";
 $result = mysqli_query($conn, $sql);
 $row = $result -> fetch_assoc();
 ?>
 
<style>
    .table {
        position: center;
  width: 100%;
  padding: 20px;
  border-collapse: collapse;
  /*padding: 20px;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  grid-gap: 30px;*/
}

 .profile {
  position: relative;
  background: var(--white);
  padding: 50px; 
  border-radius: 20px;
  display: flex;
  justify-content: space-between;
  
  box-shadow: 0 7px 25px rgba(0, 0, 0, 0.08);
}

th {
    text-align: center;
    
    width: 400px;
    padding: 10px;
}
td {
    text-align: left;
    
    width: 400px;
    padding: 5px;
}
tr:hover{
    background-color:coral;
}
tr:nth-child(even) {
    background-color: #f2f2f2;
}
.button1{
    background-color: blue; /* Green */
  border: none;
  color: white;
  padding: 10px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
  border-radius: 10px;
}
</style>
<div class="cardBox">
                <div class="card">
                    <div>
                        <div class="numbers">1,504</div>
                        <div class="cardName">Daily Views</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="eye-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div class="numbers">80</div>
                        <div class="cardName">Sales</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="cart-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div class="numbers">284</div>
                        <div class="cardName">Comments</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="chatbubbles-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div class="numbers">$7,842</div>
                        <div class="cardName">Earning</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="cash-outline"></ion-icon>
                    </div>
                </div>
                
</div>
<div class="table">
                    <table class="profile">
                        <tr>
                            <th>Company</th>
                            <th>Contact</th>
                            <th>Country</th>
                        </tr>
                        <tr>
                            <td>Alfreds Futterkiste</td>
                            <td>Maria Anders</td>
                            <td>Germany</td>
                        </tr>
                        <tr>
                            <td>Centro comercial Moctezuma</td>
                            <td>Francisco Chang</td>
                            <td>Mexico</td>
                        </tr>
                        <tr>
                            <td>Centro comercial Moctezuma</td>
                            <td>Francisco Chang</td>
                            <td>Mexico</td>
                        </tr>
                         <tr>
                            <td>Centro comercial Moctezuma</td>
                            <td>Francisco Chang</td>
                            <td>Mexico</td>
                        </tr>
                    </table> <br>
                    <a href="#" class="button1">Button</a>
                </div>
