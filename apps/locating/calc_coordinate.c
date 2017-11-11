/*add by xialiang,20140811*/
#include <stdio.h>
#include <string.h>
#include <math.h>
#include <stdlib.h>

 #include "locating.h"

#define backward 0
#define forward 1

int getid_max_value(int *name)//��ȡ�������ֵ�����
{	
	int i,tmp = -10000,tmpid=0;
	for(i=0;i<3;i++)
		{
			if(name[i] > tmp)
				{
					tmp = name[i];
					tmpid=i;
				}
		}
	return tmpid;
}

int getid_min_value(int *name)
{	
	int i,tmp = 10000,tmpid=0;
	for(i=0;i<3;i++)
		{
			if(name[i] < tmp)
				{
					tmp = name[i];
					tmpid=i;
				}
		}
	return tmpid;
}

int getid_2nd_value(int *name)
{
	int id = 3-getid_max_value(name) - getid_min_value(name);
	return id;
}
int move_sta(int src_x,int src_y,int dest_x,int dest_y,int feedback,int *sta_x_y,int direction)
{
	sta_x_y[0] = src_x;
	sta_x_y[1] = src_y;
	if(src_x > dest_x)
		sta_x_y[0] = (direction == forward )? (src_x - feedback ): (src_x + feedback);
	else if(src_x < dest_x)
		sta_x_y[0] = (direction == forward )? (src_x + feedback ): (src_x - feedback);

	if(src_y > dest_y)
		sta_x_y[1] = (direction == forward )? (src_y - feedback ): (src_y + feedback);
	else if(src_y < dest_y)
		sta_x_y[1] = (direction == forward )? (src_y + feedback ): (src_y - feedback);
		
}
/*������ap������Ϊ�߽磬�������߽�1�ף���ֹ��㳬����Χ*/
int fix_sta_x_y(int *x,int *y,int *sta_x_y,int multiple)
{
	#if 1
	int i=0,tmp_value=0,tmpid=0,tmpid3 = 0;
	tmpid = getid_max_value(x);
	tmpid3 = getid_min_value(x);
	if(sta_x_y[0]<x[tmpid3])
		sta_x_y[0] =  x[tmpid3] - multiple;
	if(sta_x_y[0]>x[tmpid])
		sta_x_y[0] =  x[tmpid] + multiple;

	tmpid = getid_max_value(y);
	tmpid3 = getid_min_value(y);
	if(sta_x_y[1]<y[tmpid3])
		sta_x_y[1] =  y[tmpid3] - multiple;
	if(sta_x_y[1]>y[tmpid])
		sta_x_y[1] =  y[tmpid] + multiple;
	#endif
	return 0;
}

int calc_sta_coordinate(int *rssi, int *x, int *y, int *sta_x_y, int multiple,int *refer_rssi)  //����Բ֮��Ľ����������
{
	int zero_count = 0;/*0ֵapͳ��*/
	int best_sta_count = 0;/*��Ϊ����ն˵�ap��ͳ��*/
	int weak_sta_count = 0;/*��Ϊ�ϲ��ն˵�ap��ͳ��*/
	int good_sta_count = 0;/*��Ϊһ���ն˵�ap��ͳ��*/
	int ap_flag = 0;
	int i=0,tmp_value=0,tmpid=0,tmpid2=0,tmpid3 = 0;
	srand((int)time(0));
	if(refer_mode=0)
	{
		for(i=0;i<3;i++)
			refer_rssi[i] = best_rssi;
	}
		
	for(i=0;i<3;i++)
	{
		if (rssi[i] == 0)
			zero_count++;
		if (rssi[i] >=refer_rssi[i]-5)
			best_sta_count++;
		if (rssi[i] <= weak_rssi)
			weak_sta_count++;
		if (rssi[i]<refer_rssi[i]-4 && rssi[i] > weak_rssi)
			good_sta_count++;
	}
	tmpid = getid_max_value(rssi);
	tmpid2 = getid_2nd_value(rssi);
	tmpid3 = getid_min_value(rssi);
	if(rssi[tmpid] < weak_rssi)
	{
		print_info("all_weak,so give up it !\n");
		return -1;
	}
	switch(zero_count)
	{	
		case 1:
			print_info("zero_count=1\n");
			/*ѡ�����ap����ڶ�ap�ƶ���Ȼ������������ƶ�*/
			//tmp_value = (refer_rssi[tmpid] - rssi[tmpid] - 4) > 0  ?  (rssi[tmpid2] - 48)*multiple : multiple;
			//tmp_value = 2*multiple;
			if(refer_rssi[tmpid] - rssi[tmpid] - 4 > 0)
			{/*��õ�һap��Զ*/
				sta_x_y[0] = (x[tmpid] + x[tmpid2])/2;
				sta_x_y[1] = (y[tmpid] + y[tmpid2])/2;
			}else
			{	/*��õ�һap�Խ�*/
			move_sta(x[tmpid],y[tmpid],x[tmpid2],y[tmpid2],2*multiple,sta_x_y,forward);
			}
			move_sta(sta_x_y[0],sta_x_y[1],x[tmpid3],y[tmpid3],3*multiple,sta_x_y,backward);
			fix_sta_x_y(x,y,sta_x_y,multiple);
			return 0;
		case 2:
			print_info("zero_count=2\n");
			sta_x_y[0] = x[tmpid]  + (rand()%2-1)*multiple;
			sta_x_y[1] = y[tmpid]  + (rand()%2-1)*multiple;
			fix_sta_x_y(x,y,sta_x_y,multiple);
		return 0;
	}
	switch(best_sta_count)
	{
		case 1:
			print_info("best_sta_count=1\n");
			if(rssi[tmpid]-rssi[tmpid3] < 14)
			{/*��������������*/
				print_info("random center\n");
				tmp_value = (rand()%2) * multiple;
				move_sta(x[tmpid],y[tmpid],x[tmpid2],y[tmpid2],tmp_value,sta_x_y,forward);
				if(good_sta_count == 2)
				{	
					tmp_value = (rand()%2) * multiple;
					move_sta(sta_x_y[0],sta_x_y[1],x[tmpid2],y[tmpid2],2*multiple,sta_x_y,forward);
					tmp_value = (rand()%2) * multiple;
					move_sta(sta_x_y[0],sta_x_y[1],x[tmpid3],y[tmpid3],tmp_value,sta_x_y,forward);
				}
				else
				{	
					tmp_value = (rand()%2) * multiple;
					move_sta(sta_x_y[0],sta_x_y[1],x[tmpid2],y[tmpid2],tmp_value,sta_x_y,forward);
					tmp_value = (rand()%2) * multiple;
					move_sta(sta_x_y[0],sta_x_y[1],x[tmpid3],y[tmpid3],tmp_value,sta_x_y,backward);
				}
			}else
			{
				print_info("out center\n");
				tmp_value = rssi[tmpid] - rssi[tmpid3] - 14;
				move_sta(x[tmpid],y[tmpid],x[tmpid3],y[tmpid3],tmp_value*multiple,sta_x_y,backward);
				if(rssi[tmpid]-rssi[tmpid2] < 14)
					move_sta(sta_x_y[0],sta_x_y[1],x[tmpid2],y[tmpid2],multiple,sta_x_y,forward);
				else
					move_sta(sta_x_y[0],sta_x_y[1],x[tmpid2],y[tmpid2],multiple,sta_x_y,backward);
			}
			fix_sta_x_y(x,y,sta_x_y,multiple);
			return 0;
		case 2:
			print_info("best_sta_count=2\n");
			/*������ѡ���м�ڵ㣬Ȼ�������ƶ�*/
			sta_x_y[0] = (x[tmpid] + x[tmpid2])/2;
			sta_x_y[1] = (y[tmpid] + y[tmpid2])/2;
			move_sta(sta_x_y[0],sta_x_y[1],x[tmpid],y[tmpid],multiple,sta_x_y,forward);
			tmp_value = (rand()%2) * multiple;
			move_sta(sta_x_y[0],sta_x_y[1],x[tmpid3],y[tmpid3],tmp_value,sta_x_y,forward);
			fix_sta_x_y(x,y,sta_x_y,multiple);
			return 0;
		case 3:
			print_info("best_sta_count=3\n");
			/*����*/
			sta_x_y[0] = (x[tmpid] + x[tmpid2] + x[tmpid3])/3;
			sta_x_y[1] = (y[tmpid] + y[tmpid2] + x[tmpid3])/3;
			fix_sta_x_y(x,y,sta_x_y,multiple);
		return 0;
	}
	switch(good_sta_count)
	{
		case 1:
			print_info("good_sta_count=1\n");
			/*ѡ��ap�������������ƶ�*/
			move_sta(x[tmpid],y[tmpid],x[tmpid2],y[tmpid2],2*multiple,sta_x_y,backward);
			move_sta(sta_x_y[0],sta_x_y[1],x[tmpid3],y[tmpid3],2*multiple,sta_x_y,backward);
			fix_sta_x_y(x,y,sta_x_y,multiple);
			return 0;
		case 2:
			print_info("good_sta_count=2\n");
			/*��������ѡ���м�ڵ㣬�����ƶ�*/
			sta_x_y[0] = (x[tmpid] + x[tmpid2])/2;
			sta_x_y[1] = (y[tmpid] + y[tmpid2])/2;
			tmp_value = (rand()%2) * multiple;
			move_sta(sta_x_y[0],sta_x_y[1],x[tmpid],y[tmpid],tmp_value,sta_x_y,forward);
			tmp_value = (rand()%2+1) * multiple;
			move_sta(sta_x_y[0],sta_x_y[1],x[tmpid3],y[tmpid3],tmp_value,sta_x_y,backward);
			fix_sta_x_y(x,y,sta_x_y,multiple);
			return 0;
		case 3:
			print_info("good_sta_count=3\n");
			tmp_value = (rssi[tmpid] + rssi[tmpid2] + rssi[tmpid3])/3;
			/*�ж��ź��Ƿ���ȷֲ�*/
			if(abs(rssi[tmpid] - tmp_value) > 3 || abs(rssi[tmpid2] - tmp_value) > 3 || abs(rssi[tmpid3] - tmp_value) > 3)
			{      if(rssi[tmpid] - rssi[tmpid3] >= 14)
				{/*�ն˿�������������*/
					print_info("out center\n");
					if(rssi[tmpid] - rssi[tmpid2] <= 4)
					{/*���ap�ĶԱ�������*/
						sta_x_y[0] = (x[tmpid] + x[tmpid2])/2;
						sta_x_y[1] = (y[tmpid2] + y[tmpid2] )/2;
						move_sta(sta_x_y[0],sta_x_y[1],x[tmpid3],y[tmpid3],2*multiple,sta_x_y,backward);
					}else
					{/*�����apλ�ã���Զ��������ap*/
						tmp_value = rssi[tmpid] - rssi[tmpid3] - 14;
						move_sta(sta_x_y[0],sta_x_y[1],x[tmpid3],y[tmpid3],tmp_value*multiple,sta_x_y,backward);
						move_sta(sta_x_y[0],sta_x_y[1],x[tmpid2],y[tmpid2],2*multiple,sta_x_y,backward);
					}
				}else
				{
					print_info("near center\n");
					sta_x_y[0] = (x[tmpid] + x[tmpid2] + x[tmpid3])/3;
					sta_x_y[1] = (y[tmpid] + y[tmpid2] + y[tmpid3])/3;
					move_sta(sta_x_y[0],sta_x_y[1],x[tmpid],y[tmpid],2*multiple,sta_x_y,forward);
					move_sta(sta_x_y[0],sta_x_y[1],x[tmpid2],y[tmpid2],multiple,sta_x_y,forward);
				}
			}else
			{print_info("random center\n");
				srand((int)time(0));
				sta_x_y[0] = (x[tmpid] + x[tmpid2] + x[tmpid3])/3 + (rand()%2-1)*multiple;
				sta_x_y[1] = (y[tmpid] + y[tmpid2] + y[tmpid3])/3 + (rand()%2-1)*multiple;
			}
			fix_sta_x_y(x,y,sta_x_y,multiple);
			return 0;
	}
	return 0;
}

