<form action="/public/index.php?url=update-status" method="POST">
    <input type="hidden" name="userId" value="<?php echo $row['id']; ?>">
    <select name="status" class="border rounded p-2">
        <option value="Active" <?php echo $row['status'] === 'Active' ? 'selected' : ''; ?>>Active</option>
        <option value="Pending" <?php echo $row['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
        <option value="Suspended" <?php echo $row['status'] === 'Suspended' ? 'selected' : ''; ?>>Suspended</option>
    </select>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
</form>
<?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-500 text-white p-4 rounded mb-4">
        <?php 
        echo $_SESSION['success'];
        unset($_SESSION['success']); 
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="bg-red-500 text-white p-4 rounded mb-4">
        <?php 
        echo $_SESSION['error'];
        unset($_SESSION['error']); 
        ?>
    </div>
<?php endif; ?>
