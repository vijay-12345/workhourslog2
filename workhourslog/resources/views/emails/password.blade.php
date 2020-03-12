
<tr>
   <td class='movableContentContainer' valign='top' style="padding-top: 20px;">
      
      <div class='movableContent'>
         <table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
               <td align='left'>
                  <div class="contentEditableContainer contentTextEditable">
                     <div class="contentEditable" align='center'>
                        <h2>Hi {{$user['name']}},</h2>
                     </div>
                  </div>
               </td>
            </tr>
            <tr>
               <td height='15'> </td>
            </tr>
            <tr>
               <td align='left'>
                  <div class="contentEditableContainer contentTextEditable">
                     <div class="contentEditable" align='center'>
                        <p  style='text-align:left;color:#999999;font-size:14px;font-weight:normal;line-height:19px;'>
                           received message
                           <br>
                           <br>
                           <a href="{{ ('http://localhost:3000/reset_password?'.$user['token']) }}" title="Reset Password">email reset_password</a>
                           <br>
                           <br>
                           
                           <br>
                           <br>
                           email copy_link
                           <br>
                           <a href="{{ ('http://localhost:3000/reset_password?'.$user['token']) }}">{{ ('http://localhost:3000/reset_password?'.$user['token']) }}</a>
                        </p>
                     </div>
                  </div>
               </td>
            </tr>
            <tr>
               <td height='20'></td>
            </tr>
         </table>
      </div>
   </td>
</tr>