# coding = utf-8 
import requests 
import time #计时用的 
import pymysql 
""" 
连接数据库 返回：
cursor,conn 
""" 
def connect_db(): 
	conn = pymysql.connect(host='localhost', 
							port=3306, 
							user='sql139_199_127_', 
							passwd='bbH8esADPi', 
							db='sql139_199_127_', 
							charset='utf8') # 使用 cursor()方法获取操作游标 
	cursor = conn.cursor(cursor=pymysql.cursors.DictCursor) 
	return cursor,conn 
""" 
得到天气信息的json内容 
输入：citycode - 城市的citycode 
返回：r.text - json格式的文件 
""" 
def get_info(citycode='101010100'): 
	url = "http://t.weather.sojson.com/api/weather/city/" + citycode 
	r = requests.get(url) 
	return r.text 
""" 
更新天气信息内容 
""" 
def update_info(): 
	#连接数据库 
	cursor,conn= connect_db() 
	#去数据库查询所有的citycode 
	cursor.execute('select weather_code from ins_county') 
	listcodes = cursor.fetchall() # 得到多行记录 
	conn.commit() #更新数据库的weather_info 
	for citycode in listcodes: 
		time.sleep(0.3) # 设置时间间隔为 0.3秒,因为这个接口阈值为每分钟300次，也就是每0.2秒一次，这里保险点用0.3秒 
		info = get_info(citycode['weather_code']) # 将字符串数据存入数据库
		sql = "UPDATE ins_county SET weather_info = '%s' WHERE weather_code = %s" %(info,citycode['weather_code']) 
		try: # 执行 SQL 语句 
			cursor.execute(sql) 
			# 提交到数据库执行 
			conn.commit() 
			print("{}写入成功".format(citycode['weather_code'])) 
		except: # 发生错误时回滚 
			conn.rollback() 
if __name__ == '__main__': 
	update_info()
