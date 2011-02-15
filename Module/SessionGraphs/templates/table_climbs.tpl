        <!-- Climbs, expandable -->
        <div class="expand">
            <h3>Climbs</h3>
            <table class="tablesorter" id="climbs">
            <thead>
            <tr>
                <th>Climb Num</th>
                <th>Category</th>
                <th>Duration</th>
                <th>Distance</th>
                <th>Altitude</th>
                {* <th>Avg Speed</th> *}
                {* <th>Avg Heartrate</th> *}
                {* <th>Max Heartrate</th> *}
                <th>Avg Gradient</th>
                <th>Max Gradient</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$climbs key=i item=climb}
            <tr>
                <td><a href="{url module=SessionGraphs action=viewClimbs session_date=$session_date|escape:url climb_num=$climb.climb_num|escape:url}">{$climb.climb_num}</a></td>
                <td>{$climb.category}</td>
                <td>{$climb.duration}</td>
                <td>{$climb.total_distance}</td>
                <td>{$climb.total_climbed}</td>
                {* <td>{$climb.avg_speed}</td> *}
                {* <td>{$climb.avg_heartrate}</td> *}
                {* <td>{$climb.max_heartrate}</td> *}
                <td>{$climb.gradient_avg}</td>
                <td>{$climb.gradient_max}</td>
            </tr>
            {foreachelse}
            <tr>
                <td colspan=7>No climbs.</td>
            </tr>
            {/foreach}
            </tbody>
            </table>
        </div>
