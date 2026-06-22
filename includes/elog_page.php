<div class="elog-container">
    <h2>E-Logbook</h2>
    <form method="POST" action="save_elog.php">
        <table>
            <tr>
                <th>Date</th>
                <th>Task</th>
                <th>Supervisor’s Remark</th>
            </tr>
            <tr>
                <td><input type="date" name="date"></td>
                <td><input type="text" name="task"></td>
                <td><input type="text" name="remark"></td>
            </tr>
        </table>
        <button type="submit">Save</button>
    </form>
</div>
