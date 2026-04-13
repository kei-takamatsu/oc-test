<?php
App::uses('AppHelper', 'View/Helper');
App::uses('Sanitize', 'Utility');

class ProjectHelper extends AppHelper
{

    /**
     * 達成率の取得関数
     * @param array $project
     * @return int $per
     */
    public function get_backed_per($project)
    {
        if(empty($project['Project']['collected_amount'])){
            return 0;
        }else{
            return (int)(($project['Project']['collected_amount'] / $project['Project']['goal_amount']) * 100);
        }
    }

    /**
     * 残日数の取得関数
     * 残り0日の場合は○時間○分で表示
     * @param array $project
     * @param bool  $full trueの場合○時間○分で返し、falseの場合○時間で返す
     * @return string
     */
    public function get_zan_day($project, $full = false)
    {
        if(!empty($project['Project']['collection_end_date'])){
            $current_date = new DateTime(date('Y-m-d,H:i:s'));
            $closing_date = new DateTime($project['Project']['collection_end_date']);
            $day_remains  = 0;
            if($closing_date > $current_date){
                $day_remains = date_diff($current_date, $closing_date);
                $zan_day     = $day_remains->format('%a日');
                if($zan_day == '0日'){
                    if($full){
                        return $day_remains->h.'時間'.$day_remains->i.'分';
                    }else{
                        return $day_remains->h.'時間';
                    }
                }else{
                    return $zan_day;
                }
            }else{
                return '終了済み';
            }
        }else{
            return '未開始';
        }
    }

    /**
     * 支援可能人数のラベル取得関数
     * max_countが空あるいは0の場合、制限なしなのでnullを返す
     * @param array $backing_level
     * @return string
     */
    public function get_zan_back_label($backing_level)
    {
        if(!empty($backing_level['max_count'])){
            if($backing_level['max_count'] <= $backing_level['now_count']){
                return '<span class="label label-default">OUT OF STOCK</span>';
            }else{
                return '<span class="label label-info">残り'.($backing_level['max_count'] - $backing_level['now_count'])
                       .'人</span>';
            }
        }else{
            return null;
        }
    }

    /**
     * 支援可能か確認する関数（最大支援回数に到達していないか確認する）
     * @param array $backing_level
     * @return boolean
     */
    public function check_back_available($backing_level)
    {
        if(!empty($backing_level['max_count'])){
            if($backing_level['max_count'] <= $backing_level['now_count']){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    }

    /**
     * プロジェクトテキストのサニタイズ関数
     * @param string $text
     * @return string $text
     */
    public function sanitize($text)
    {
        return Sanitize::stripTags($text, 'div', 'img', 'script', 'code', 'pre', 'table', 'tr', 'td', 'ul', 'ol', 'li', 'dd', 'dt', 'movie', 'iframe', 'form', 'input', 'select', 'option', 'button', 'object', 'style');
    }

}