        <!-- Session laps, expandable -->
        <div class="expand">
            <h3>Laps</h3>
            <table class="tablesorter" id="laps">
            <thead>
            <tr>
                <th>Lap Num</th>
                <th>Duration</th>
                <th>Distance</th>
                <th>Avg Speed</th>
                <th>Max Speed</th>
                <th>Avg Heartrate</th>
                <th>Max Heartrate</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$laps key=i item=lap}
            <tr>
                <td><a href="{url module=SessionGraphs action=viewLaps session_date=$session_date|escape:url lap_num=$lap.lap_num|escape:url}">{$lap.lap_num}</a></td>
                <td>{$lap.duration}</td>
                <td>{$lap.distance}</td>
                <td>{$lap.avg_speed}</td>
                <td>{$lap.max_speed}</td>
                <td>{$lap.avg_heartrate}</td>
                <td>{$lap.max_heartrate}</td>
            </tr>
            {foreachelse}
            <tr>
                <td colspan=7>No laps found.</td>
            </tr>
            {/foreach}
            </tbody>
            </table>
        </div>
        <br />
