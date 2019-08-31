@ ARM Assembly - lab 2
@ 
@ Roshan Ragel - roshanr@pdn.ac.lk
@ Hasindu Gamaarachchi - hasindu@ce.pdn.ac.lk

	.text 	@ instruction memory
	.global main
main:
	@ stack handling, will discuss later
	@ push (store) lr to the stack
	sub sp, sp, #4
	str lr, [sp, #0]

	@ Load REQUIRED VALUES HERE
	@ load i to r0
	@ load j to r1
	@ load sum to r5

	
	@ Write YOUR CODE HERE
	
	@	Sum = 0;
	@	for (i=0;i<10;i++){
	@			for(j=5;j<15;j++){
	@				if(i+j<10) sum+=i*2
	@				else sum+=(i&j);	
	@			}	
	@	} 
	@ Put final sum to r5


	@ r0 = i
	@ r1 = j 
	@ r8 = i+j
	@ r5 = sum
	@ r10 = i&j

			mov r5, #0;
			mov r0, #0;	

BEGIN_I:	cmp r0, #10;
			bge EXIT_I;		@ exit if r0>=10
							@ else continue 

			mov r1, #5; 	@ r1 = 15

BEGIN_J:	cmp r1, #15;	 
			bge EXIT_J;		@ exit if r1>=15
							@ else continue

			@ --- begin if condition ------------------------------------------

			add r8, r0, r1; @ (r8 = i + j)
			cmp r8, #10
			ble ELSE;

IF:			add r5, r5, r0; @ sum = sum + i
			add r5, r5, r0; @ sum = sum + i			
			b ENDIF

ELSE:		and r10, r0, r1;
			add r5, r5, r10;
ENDIF:

			@ --- end of if condition ------------------------------------------

			add r1, r1, #1;	@ j = j + 1
			b BEGIN_J;

EXIT_J:
			add r0, r0, #1;	@ i = i + 1
			b BEGIN_I;		@ return to begining of i 

EXIT_I:	
	
	@ load aguments and print
	ldr r0, =format
	mov r1, r5
	bl printf

	@ stack handling (pop lr from the stack) and return
	ldr lr, [sp, #0]
	add sp, sp, #4
	mov pc, lr

	.data	@ data memory
format: .asciz "The Answer is %d (Expect 300 if correct)\n"

