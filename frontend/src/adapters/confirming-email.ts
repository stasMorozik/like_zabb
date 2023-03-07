import { Either, left, right } from "@sweet-monads/either";
import { ajax } from 'rxjs/ajax';
import { catchError, of, Subject, Observable, switchMap } from 'rxjs';
import { ConfirmingEmailUseCase } from '../use-cases/confirming-email';

export namespace ConfirmingEmailAdapters {
  export class Emiter implements ConfirmingEmailUseCase.Ports.Emiter {
    constructor(
      private readonly _subject: Subject<Either<Error, boolean>>
    ){}

    emit(either: Either<Error, boolean>): void {
      this._subject.next(either);
    }
  }

  export class Api implements ConfirmingEmailUseCase.Ports.Api {
    fetch(dto: ConfirmingEmailUseCase.Dtos.Data): Observable<Either<Error, boolean>>  {
      return ajax({
        url: '/api/confirmation-codes/',
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: dto
      }).pipe(
        switchMap(() => {
          return of(right(true));
        }),
        catchError((error) => {
          //need middleware or interceptor
          if (error.status == 400) {
            return of(left(new Errors.Infrastructure.BadRequest(error.response.message)));
          }
          if (error.status == 404) {
            return of(left(new Errors.Infrastructure.NotFound(error.response.message)));
          }
          if (error.status == 500) {
            return of(left(new Errors.Infrastructure.InternalServerError(error.response.message)));
          }
          return of(right(true));
        })
      );
    }
  }
}
