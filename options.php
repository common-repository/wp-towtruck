<?php
$options = get_option('wptowtruck');

if (isset($_POST['defaults']))
{
    if (!check_admin_referer()) die('Securety violated');
    update_option('wptowtruck', $wptowtruck_default_options);
    $options = $wptowtruck_default_options;
}

if (isset($_POST['save']))
{
    if (!check_admin_referer()) die('Securety violated');
    $options = stripslashes_deep($_POST['options']);
    update_option('wptowtruck', $options);
}
?>
<div class="wrap">
  
    <h2>WP TowTruck</h2>

    <form method="post">
        <?php wp_nonce_field() ?>

		<div id="tabs">
      		<ul>
        		<li><a href="#tabs-1">General configurations</a></li>
        		<li><a href="#tabs-2">Layout</a></li>        		
      		</ul>
        
        	<div id="tabs-1">
        	
		        <table class="form-table">
		            <tr valign="top">
		                <th><label>Activate TowTruck button</label></th>
		                <td>
		                	<input type="checkbox" name="options[activate]" value="1" <?php echo $options['activate']=='1'?'checked="checked"':''; ?> />
		                	<div>
		                		Activate the button (be aware of any caching system already active on your blog).
		                	</div>
		                </td>                    
		            </tr>
		            <tr valign="top">
		                <th><label>Enable only for logged users</label></th>
		                <td>
		                	<input type="checkbox" name="options[loggedusers]" value="1" <?php echo $options['loggedusers']=='1'?'checked="checked"':''; ?> />
		                	<div>
		                		Show the button only for logged in users.
		                	</div>
		                </td>                    
		            </tr>
		            <tr valign="top">
		                <th><label>Enable TowTruck analytics</label></th>
		                <td>
		                	<input type="checkbox" name="options[analytics]" value="1" <?php echo $options['analytics']=='1'?'checked="checked"':''; ?> />
		                	<div>
		                		Enable Mozilla TowTruck analytics.
		                	</div>
		                </td>                    
		            </tr>		            		        	     	
		        </table>
		        
			</div>
			
			<div id="tabs-2">
			
				<table class="form-table">
		        	<tr valign="top">
		                <th><label>Choose button text</label></th>
		                <td>
		                	<input type="text" name="options[button-text]" value="<?php echo $options['button-text']==""? $wptowtruck_default_options['button-text']: $options['button-text']; ?>" />
		                </td>
		            </tr>
		        	<tr valign="top">
		                <th><label>Customize button style</label><br><button type="button" onclick="jQuery('#wp_towtruck_options_style').val('');">Clear custom style</button></th>
		                <td>
		                	<textarea  id="wp_towtruck_options_style" name="options[style]" rows="5" cols="75"><?php echo htmlspecialchars($options['style'])?></textarea>
		                	<div>
		                		Customize css button style (leave blank to use default style).<br>
		                		The html element is a &lt;button&gt; with id 'start-towtruck'. Your style should be something like this:<br>
		                		<i>#start-towtruck {<br>
										position:fixed;<br>
										left:0px;<br>
										bottom:0px;<br>
										height:30px;<br>
										width:150px;<br>
										background:#999;<br>
										cursor:pointer;<br>
									}</i>                		
		                	</div>
		                </td>                    
		            </tr>     	
		        </table>
			
			</div>		       

		</div>			
			
        <p class="submit">
            <input type="submit" name="save" value="Save"/>
            <input type="submit" name="defaults" value="Revert to Defaults" onclick="return confirm('Are you sure you want to revert to defaults?')"/>
        </p>
        
    </form>
    
</div>

