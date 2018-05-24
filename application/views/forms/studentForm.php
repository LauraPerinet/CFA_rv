
<?php echo form_open('student/update/'.$student->id); ?>

<h5>Email</h5>
<input type="text" name="email" value="<?php echo $student->email;?>" size="50" />
<h5>Téléphone</h5>
<input type="text" name="phone" value="<?php echo $student->phone;?>" size="50" />
<input type="hidden" name="type" value="<?php echo $type;?>"/>
<div><input type="submit" value="Modifier" /></div>
</form>
